package net.madarco.lightview.UploadApplet;

import java.util.Locale;
import java.util.MissingResourceException;
import java.util.ResourceBundle;

public class I18NMessages {
	private static final String BUNDLE_NAME = "net.madarco.lightview.UploadApplet.I18NMessages"; //$NON-NLS-1$

	private static ResourceBundle RESOURCE_BUNDLE = null;

	private I18NMessages() {
	}

	public static String getString(String key) {
		if(RESOURCE_BUNDLE == null) {
			Locale locale = Locale.getDefault();
			try {
				RESOURCE_BUNDLE = ResourceBundle.getBundle(BUNDLE_NAME, locale);
			} catch (RuntimeException e) {
				//locale = Locale.ENGLISH;
				RESOURCE_BUNDLE = ResourceBundle.getBundle(BUNDLE_NAME, locale);
			}
		}
		try {
			return RESOURCE_BUNDLE.getString(key);
		} catch (MissingResourceException e) {
			return '!' + key + '!';
		}
	}
}
