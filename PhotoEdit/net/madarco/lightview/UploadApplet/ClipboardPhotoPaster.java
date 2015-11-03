/**
 *
 */
package net.madarco.lightview.UploadApplet;

import java.awt.Image;
import java.awt.Toolkit;
import java.awt.datatransfer.Clipboard;
import java.awt.datatransfer.DataFlavor;
import java.awt.datatransfer.Transferable;
import java.awt.datatransfer.UnsupportedFlavorException;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.io.IOException;

class ClipboardPhotoPaster implements ActionListener {
	public void actionPerformed(ActionEvent e) {
		
	}

	public Image getImageFromClipboard() throws UnsupportedFlavorException, IOException {
		Image result = null;
	    Clipboard clipboard = Toolkit.getDefaultToolkit().getSystemClipboard();
	    //odd: the Object param of getContents is not currently used
	    Transferable contents = clipboard.getContents(null);
	    boolean hasTransferablePhoto =
	      (contents != null) &&
	      contents.isDataFlavorSupported(DataFlavor.imageFlavor);
	    if ( hasTransferablePhoto ) {
	      result = (Image)contents.getTransferData(DataFlavor.imageFlavor);
	    }
	    return result;
	}
}