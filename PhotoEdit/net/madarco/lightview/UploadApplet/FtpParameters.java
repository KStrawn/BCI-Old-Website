package net.madarco.lightview.UploadApplet;

import java.util.MissingResourceException;
import java.util.ResourceBundle;

public class FtpParameters {
	private static final String BUNDLE_NAME = "net.madarco.lightview.UploadApplet.FtpParameters"; //$NON-NLS-1$

	private static final ResourceBundle RESOURCE_BUNDLE = ResourceBundle
			.getBundle(BUNDLE_NAME);

	private FtpParameters() {
	}

	public static String getString(String key) {
		try {
			return RESOURCE_BUNDLE.getString(key);
		} catch (MissingResourceException e) {
			return '!' + key + '!';
		}
	}
}
