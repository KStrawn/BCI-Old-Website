/**
 *
 */
package net.madarco.lightview.UploadApplet;

import java.awt.BorderLayout;
import javax.swing.JPanel;
import javax.swing.JApplet;

/**
 * @author Madarco
 *
 */
public class FtpApplet extends JApplet {

	private JPanel jContentPane = null;
	private UploadFrame applet;  //  @jve:decl-index=0:

	/**
	 * This is the xxx default constructor
	 */
	public FtpApplet() {
		super();
	}

	/**
	 * This method initializes this
	 *
	 * @return void
	 */
	public void init() {
		this.setSize(300, 200);
		this.applet = new UploadFrame();
		this.applet.init();
		jContentPane = new JPanel();
		jContentPane.setLayout(new BorderLayout());
		this.setContentPane(applet.getJContentPane());
	}

}
