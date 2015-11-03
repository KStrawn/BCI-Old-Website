
import java.awt.dnd.DropTarget;
import java.awt.event.KeyEvent;
import java.awt.event.ActionListener;
import java.awt.event.ActionEvent;
import java.awt.Event;
import java.awt.BorderLayout;
import javax.swing.SwingConstants;
import javax.swing.SwingUtilities;
import javax.swing.KeyStroke;
import java.awt.Point;
import javax.swing.JLabel;
import javax.swing.JPanel;
import javax.swing.JMenuItem;
import javax.swing.JMenuBar;
import javax.swing.JMenu;
import javax.swing.JFrame;
import javax.swing.JDialog;
import javax.swing.UIManager;

import net.madarco.lightview.UploadApplet.UploadFrame;

public class FtpDroplet {

	private JFrame jFrame = null;

	protected UploadFrame applet;  //  @jve:decl-index=0:

	/**
	 * @param args
	 */
	public static void main(String[] args) {
		// TODO Auto-generated method stub
		SwingUtilities.invokeLater(new Runnable() {
			public void run() {
				FtpDroplet application = new FtpDroplet();
				application.getJFrame().setVisible(true);
			}
		});
	}

	/**
	 * This method initializes jFrame
	 *
	 * @return javax.swing.JFrame
	 */
	private JFrame getJFrame() {
		if (jFrame == null) {
			String systemLookAndFeelClassName = UIManager.getSystemLookAndFeelClassName();
			try {
				UIManager.setLookAndFeel(systemLookAndFeelClassName);
			} catch (Exception e) {
				;
			}

			this.applet = new UploadFrame();
			this.applet.init();

			jFrame = new JFrame();
			jFrame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
			jFrame.setSize(400, 400);
			jFrame.setContentPane(applet.getJContentPane());
			jFrame.setTitle("Application");
		}
		return jFrame;
	}

}
