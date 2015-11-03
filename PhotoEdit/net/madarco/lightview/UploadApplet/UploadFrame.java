/**
 *
 */
package net.madarco.lightview.UploadApplet;

import java.awt.BorderLayout;
import java.awt.Component;
import java.awt.Cursor;
import java.awt.FlowLayout;
import java.awt.HeadlessException;
import java.awt.dnd.DropTarget;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;

import javax.swing.JApplet;
import javax.swing.JButton;
import javax.swing.JLabel;
import javax.swing.JPanel;
import javax.swing.JProgressBar;
import javax.swing.JTree;
import javax.swing.JWindow;
import javax.swing.event.TreeSelectionEvent;
import javax.swing.event.TreeSelectionListener;
import javax.swing.tree.DefaultMutableTreeNode;
import javax.swing.tree.DefaultTreeModel;
import javax.swing.tree.TreeSelectionModel;

/**
 * @author Madarco
 *
 */
public class UploadFrame {

	/**
	 *
	 */
	private static final long serialVersionUID = 1L;

	JPanel jContentPane = null;
	private JButton upload = null;
	private FtpUploader upl;  //  @jve:decl-index=0:
	private JPanel jBottomPanel = null;
	private JLabel jStatusLabel = null;
	JProgressBar jProgressBar = null;
	private JPanel jStatusPanel = null;
	JTree jPhotosTree = null;
	private DefaultMutableTreeNode rootNode;
	private DefaultTreeModel treeModel;
	private Thread uploadThread;

	private DropTarget target;

	private DropFilesHandler handler;

	private JPanel jButtonsPanel;

	private JButton pastePhoto;

	/**
	 * This method initializes this
	 *
	 * @return void
	 */
	public void init() {
		//this.createUploader();
		//this.setContentPane(getJContentPane());
		//this.target = new DropTarget(this.jPhotosTree, getDropHandler());

	}

	public DropFilesHandler getDropHandler() {
		if(this.handler == null) {
			this.handler = new DropFilesHandler(this, this.treeModel, this.rootNode);
		}
		return this.handler;
	}

	/**
	 * This method initializes jContentPane
	 *
	 * @return javax.swing.JPanel
	 */
	public JPanel getJContentPane() {
		if (jContentPane == null) {
			jContentPane = new JPanel();
			jContentPane.setLayout(new BorderLayout());
			jContentPane.add(getBottomPanel(), BorderLayout.SOUTH);
			jContentPane.add(getJPhotosTree(), BorderLayout.CENTER);
			new DropTarget(jPhotosTree, getDropHandler());
			new DropTarget(jContentPane, getDropHandler());
		}
		return jContentPane;
	}

	/**
	 * This method initializes upload
	 *
	 * @return javax.swing.JButton
	 */
	private JButton getUpload() {
		if (upload == null) {
			upload = new JButton();
			upload.setText(I18NMessages.getString("UploadFrame.Load_pictures")); //$NON-NLS-1$

			upload.addActionListener( new ActionListener() {
					public void actionPerformed(ActionEvent e) {
					upload.setEnabled(false);
					upl = new FtpUploader(UploadFrame.this);
					Thread uploadThread = new Thread(upl);
					uploadThread.start();
					//setCursor(Cursor.getPredefinedCursor(Cursor.WAIT_CURSOR));
					//upl.upload();
					//setCursor(Cursor.getPredefinedCursor(Cursor.DEFAULT_CURSOR));
				}
			});
		}
		return upload;
	}

	public synchronized void enableUpload() {
		upload.setEnabled(true);
	}

	/**
	 * This method initializes jButtonsPanel
	 *
	 * @return javax.swing.JPanel
	 */
	private JPanel getBottomPanel() {
		if (jBottomPanel == null) {
			BorderLayout borderLayout = new BorderLayout();
			borderLayout.setHgap(0);
			jStatusLabel = new JLabel();
			jStatusLabel.setText(I18NMessages.getString("UploadFrame.DragDrop_a_file_or_a_folder_to_begin")); //$NON-NLS-1$
			jBottomPanel = new JPanel();
			jBottomPanel.setLayout(borderLayout);
			jBottomPanel.add(getButtonsPanel(), BorderLayout.EAST);
			jBottomPanel.add(getJStatusPanel(), BorderLayout.CENTER);
		}
		return jBottomPanel;
	}

	private JPanel getButtonsPanel() {
		if (jButtonsPanel == null) {
			jButtonsPanel = new JPanel();
			jButtonsPanel.setLayout(new FlowLayout());
			jButtonsPanel.add(getUpload());
			jButtonsPanel.add(getPastePhoto());
		}
		return jButtonsPanel;
	}

	private Component getPastePhoto() {
		if (pastePhoto == null) {
			pastePhoto = new JButton();
			pastePhoto.setText(I18NMessages.getString("UploadFrame.Paste_photos")); //$NON-NLS-1$
			pastePhoto.addActionListener(new ClipboardPhotoPaster());
		}
		return pastePhoto;
	}

	/**
	 * This method initializes jProgressBar
	 *
	 * @return javax.swing.JProgressBar
	 */
	public JProgressBar getJProgressBar() {
		if (jProgressBar == null) {
			jProgressBar = new JProgressBar();
		}
		return jProgressBar;
	}

	/**
	 * This method initializes jStatusPanel
	 *
	 * @return javax.swing.JPanel
	 */
	private JPanel getJStatusPanel() {
		if (jStatusPanel == null) {
			jStatusPanel = new JPanel();
			jStatusPanel.setLayout(new BorderLayout());
			jStatusPanel.add(jStatusLabel, BorderLayout.NORTH);
			jStatusPanel.add(getJProgressBar(), BorderLayout.SOUTH);
		}
		return jStatusPanel;
	}

	/**
	 * This method initializes jPhotosTree
	 *
	 * @return javax.swing.JTree
	 */
	public JTree getJPhotosTree() {
		if (jPhotosTree == null) {
			rootNode = new DefaultMutableTreeNode(I18NMessages.getString("UploadFrame.Photos")); //$NON-NLS-1$
			treeModel = new DefaultTreeModel(rootNode);
			jPhotosTree = new JTree(treeModel);
			jPhotosTree.setRootVisible(false);
			jPhotosTree.getSelectionModel().setSelectionMode(TreeSelectionModel.SINGLE_TREE_SELECTION);
			jPhotosTree.setShowsRootHandles(true);
			jPhotosTree.setExpandsSelectedPaths(true);
			jPhotosTree.addTreeSelectionListener(new TreeSelectionListener() {
				public void valueChanged(TreeSelectionEvent e) {
					//treeModel.removeNodeFromParent((MutableTreeNode)e.getPath().getLastPathComponent());
				}

			});

		}
		return jPhotosTree;
	}

	public void setStatusText(String txt) {
		this.jStatusLabel.setText(txt);
	}
}
