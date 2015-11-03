package net.madarco.lightview.UploadApplet;

import java.io.File;

public class TreeElement {

	private final String label;
	private final File file;

	public TreeElement(String label, File file) {
		this.label = label;
		this.file = file;
	}

	public String toString() {
		return label;
	}

	/**
	 * @return the file
	 */
	public File getFile() {
		return this.file;
	}

	/**
	 * @return the name
	 */
	public String getName() {
		return this.label;
	}
}
