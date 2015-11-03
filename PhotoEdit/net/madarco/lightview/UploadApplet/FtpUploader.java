/**
 *
 */
package net.madarco.lightview.UploadApplet;

import java.io.BufferedInputStream;
import java.io.File;
import java.io.IOException;
import java.io.OutputStream;
import java.util.Enumeration;
import java.util.concurrent.ConcurrentHashMap;

import javax.swing.tree.DefaultMutableTreeNode;
import javax.swing.tree.DefaultTreeModel;

import org.apache.commons.net.ftp.FTP;
import org.apache.commons.net.ftp.FTPClient;
import org.apache.commons.net.ftp.FTPReply;

final class FtpUploader implements Runnable {
	/**
	 *
	 */
	private final UploadFrame applet;
	protected FTPClient ftp;
	private JpgResizer resizer;
	/**
	 * @param applet
	 */
	public FtpUploader(UploadFrame applet) {
		this.applet = applet;
		int width = new Integer(FtpParameters.getString("FtpUploader.thumbWidth")); //$NON-NLS-1$
		int height = new Integer(FtpParameters.getString("FtpUploader.thumbHeight")); //$NON-NLS-1$
		int quality = new Integer(FtpParameters.getString("FtpUploader.thumbQuality")); //$NON-NLS-1$
		this.resizer = new JpgResizer(width, height, quality);
	}

	public volatile Object semaphor;
	private volatile boolean stopped = false;

	public void runFtp() {
	    try {
	      this.ftp = new FTPClient();
	      int reply;
	      this.applet.setStatusText(I18NMessages.getString("FtpUploader.Connecting")); //$NON-NLS-1$
	      this.ftp.connect(FtpParameters.getString("FtpUploader.host")); //$NON-NLS-1$
	      this.applet.setStatusText(I18NMessages.getString("FtpUploader.Connected_to") + FtpParameters.getString("FtpUploader.host")); //$NON-NLS-1$ //$NON-NLS-2$
	      System.out.print(this.ftp.getReplyString());

	      // After connection attempt, you should check the reply code to verify
	      // success.
	      reply = this.ftp.getReplyCode();
	      if(!FTPReply.isPositiveCompletion(reply)) {
	        this.ftp.disconnect();
	        this.applet.setStatusText(I18NMessages.getString("FtpUploader.Connection_refused")); //$NON-NLS-1$
	      }
	      else {
	    	  if(!this.ftp.login(FtpParameters.getString("FtpUploader.username"), FtpParameters.getString("FtpUploader.password"))) { //$NON-NLS-1$ //$NON-NLS-2$
	    		  this.applet.setStatusText(I18NMessages.getString("FtpUploader.Server_auth_failed")); //$NON-NLS-1$
	    	  }
	    	  if(!FTPReply.isPositiveCompletion(this.ftp.cwd(FtpParameters.getString("FtpUploader.uploadFolder")))) { //$NON-NLS-1$
	    		  throw new IOException();
	    	  }

	    	  //Upload images
	    	  DefaultTreeModel model = (DefaultTreeModel)this.applet.jPhotosTree.getModel();
	    	  DefaultMutableTreeNode root = (DefaultMutableTreeNode)model.getRoot();
	    	  Enumeration nodesEnum = root.children();

	    	  DefaultMutableTreeNode node;
	    	  while(nodesEnum.hasMoreElements()) {
	    		  node = (DefaultMutableTreeNode)nodesEnum.nextElement();
	    		  TreeElement el = (TreeElement)node.getUserObject();
	    		  File file = el.getFile();
	    		  if(file.isFile()) {
	    			  //Is a file:
	    			  this.doUploadFile(file);
	    		  }
	    		  if(file.isDirectory()) {
	    			  //Is a directory
	    			  Enumeration nodesEnum2 = node.children();
	    			  DefaultMutableTreeNode node2;
	    			  this.ftp.makeDirectory(file.getName());
	    			  this.ftp.cwd(file.getName());
	    			  //Upload inner files
	    			  while(nodesEnum2.hasMoreElements()) {
	    				  node2 = (DefaultMutableTreeNode)nodesEnum2.nextElement();
	    				  TreeElement el2 = (TreeElement)node2.getUserObject();
			    		  File file2 = el2.getFile();
			    		  this.doUploadFile(file2);
	    			  }
	    			  this.ftp.cwd("../"); //$NON-NLS-1$
	    		  }
	    	  }

	    	  //Disconnect when done
	    	  this.applet.setStatusText(I18NMessages.getString("FtpUploader.Disconnecting...")); //$NON-NLS-1$
		      this.ftp.logout();
		      this.applet.setStatusText(I18NMessages.getString("FtpUploader.Disconnected")); //$NON-NLS-1$
	      }
	    } catch(IOException e) {
	      e.printStackTrace(); //TODO: error reporting to user
	    } catch (InterruptedException e) {
			//Error loading images
			e.printStackTrace();
		} finally {
			this.applet.enableUpload();
			if(this.ftp.isConnected()) {
		        try {
		          this.ftp.disconnect();
		        } catch(IOException ioe) {
		          ioe.printStackTrace(); //TODO: error reporting to user
		        }
		    }
	    }
	}

	protected void doUploadFile(File file) throws IOException,
			InterruptedException {

		this.applet.setStatusText(I18NMessages.getString("FtpUploader.Uploading") + file.getName() + "...");  //$NON-NLS-1$//$NON-NLS-2$

		BufferedInputStream imgStream = this.resizer.resizeImage(file.getAbsolutePath());
		this.ftp.setFileType(FTP.IMAGE_FILE_TYPE);
		OutputStream outputStream =	this.ftp.storeFileStream(file.getName());
		/*if (!FTPReply.isPositiveIntermediate(ftp.getReplyCode())) {
			setStatusText("Error uploading " + file.getName() + ": " + ftp.getReplyString());
			return;
		}*/

		try {
			int total = imgStream.available();
			this.applet.jProgressBar.setMaximum(total);
			byte[] b = new byte[8000];
			int count = 0;
			int c;
			while ((c = imgStream.read(b)) != -1) {
				count += c;
				this.applet.jProgressBar.setValue(count);
				this.applet.setStatusText(I18NMessages.getString("FtpUploader.Uploading") + file.getName() + ": " + count/1024 + "/" + total/1024 + "Kb");  //$NON-NLS-1$//$NON-NLS-2$ //$NON-NLS-3$ //$NON-NLS-4$
				outputStream.write(b);
			}
			this.applet.jProgressBar.setValue(this.applet.jProgressBar.getMaximum());
			this.applet.setStatusText("File " + file.getName() + I18NMessages.getString("FtpUploader._transfered")); //$NON-NLS-1$ //$NON-NLS-2$
		} finally {
			imgStream.close();
			outputStream.close();
			if (!this.ftp.completePendingCommand()) {
				throw new IOException();
			}

		}
	}

	public void run() {
   		runFtp();
    }
}