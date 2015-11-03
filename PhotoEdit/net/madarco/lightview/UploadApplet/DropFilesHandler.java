/**
 *
 */
package net.madarco.lightview.UploadApplet;

import java.awt.datatransfer.DataFlavor;
import java.awt.datatransfer.Transferable;
import java.awt.dnd.DnDConstants;
import java.awt.dnd.DropTargetAdapter;
import java.awt.dnd.DropTargetDragEvent;
import java.awt.dnd.DropTargetDropEvent;
import java.io.File;
import java.io.FilenameFilter;
import java.util.ArrayList;
import java.util.Arrays;

import javax.swing.tree.DefaultMutableTreeNode;
import javax.swing.tree.DefaultTreeModel;
import javax.swing.tree.TreePath;

final class DropFilesHandler extends DropTargetAdapter {
	/**
	 *
	 */
	private final DefaultTreeModel model;
	private final DefaultMutableTreeNode rootNode;
	private UploadFrame applet;
	/**
	 * @param applet
	 */
	DropFilesHandler(UploadFrame applet, DefaultTreeModel model, DefaultMutableTreeNode rootNode) {
		this.applet = applet;
		this.model = model;
		this.rootNode = rootNode;
	}

	/*
	 * (non-Javadoc)
	 *
	 * @see java.awt.dnd.DropTarget#drop(java.awt.dnd.DropTargetDropEvent)
	 */
	public synchronized void drop(DropTargetDropEvent evt) {
		try {
			Transferable tr = evt.getTransferable();
			DataFlavor[] flavors = tr.getTransferDataFlavors();
			for (int i = 0; i < flavors.length; i++) {
				if (flavors[i].isFlavorJavaFileListType()) {
					evt.acceptDrop(DnDConstants.ACTION_COPY);
					java.util.List list2 = (java.util.List) tr.getTransferData(flavors[i]);
					ArrayList<String> filenames = new ArrayList<String>();
					for (int j = 0; j < list2.size(); j++) {
						String path = list2.get(j).toString();
						filenames.add(path);
					}

					System.out.println(filenames);

					//Files or Folder added:
					if (filenames.size() > 0) {
						this.addFiles(filenames);
						evt.dropComplete(true);
						return;
					}
				}
			}
			evt.rejectDrop();

		} catch (Exception e) {
			e.printStackTrace();
			evt.rejectDrop();
		}
	}

	/* (non-Javadoc)
	 * @see java.awt.dnd.DropTarget#dragEnter(java.awt.dnd.DropTargetDragEvent)
	 */
	public synchronized void dragEnter(DropTargetDragEvent arg0) {
		 this.applet.jContentPane.requestFocusInWindow();
	}

	protected void addFiles(ArrayList<String> filenames) {
		for(int x=0; x < filenames.size(); x++) {
			File file = new File(filenames.get(x));
			if(file.isDirectory()) {
				//Show all the files in the directory
				File folder = new File(filenames.get(x));
				DefaultMutableTreeNode folderNode = new DefaultMutableTreeNode(new TreeElement(folder.getName(), folder));
				boolean newFolderInserted = false;
				
				File[] photos = file.listFiles(new FilenameFilter() {
					public boolean accept(File dir, String filename) {
						String name = filename.toLowerCase();
						if(name.endsWith(".jpg") || name.endsWith(".gif") || name.endsWith(".bmp")) { //$NON-NLS-1$ //$NON-NLS-2$ //$NON-NLS-3$
							return true;
						}
						return false;
					}
				});
				for(int f=0; f < photos.length; f++ ) {
					if(photos[f].isFile()) {
						//Add only non-empty folders:
						if(!newFolderInserted) {
							newFolderInserted = true;
							model.insertNodeInto(folderNode, rootNode,rootNode.getChildCount());
						}
						DefaultMutableTreeNode childNode = new DefaultMutableTreeNode(new TreeElement(photos[f].getName(), photos[f]));
						model.insertNodeInto(childNode, folderNode,folderNode.getChildCount());
					}
				}
			}
			else {
				//To Johannes: ignore files without a folder:
				//Add the file:
				String name = filenames.get(x).toLowerCase();
				if(file.isFile() && (name.endsWith(".jpg") || name.endsWith(".gif") || name.endsWith(".bmp"))) { //$NON-NLS-1$ //$NON-NLS-2$ //$NON-NLS-3$
					DefaultMutableTreeNode childNode = new DefaultMutableTreeNode(new TreeElement(file.getName(), file));
					model.insertNodeInto(childNode, rootNode,rootNode.getChildCount());
				}
			}
		}
		//Show all the items added:
		this.applet.getJPhotosTree().scrollPathToVisible(new TreePath(rootNode.getLastLeaf().getPath()));
	}
}