/**
 *
 */
package net.madarco.lightview.UploadApplet;

import java.awt.Container;
import java.awt.Graphics2D;
import java.awt.Image;
import java.awt.MediaTracker;
import java.awt.RenderingHints;
import java.awt.Toolkit;
import java.awt.image.BufferedImage;
import java.io.BufferedInputStream;
import java.io.BufferedOutputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.IOException;

import com.sun.image.codec.jpeg.JPEGCodec;
import com.sun.image.codec.jpeg.JPEGEncodeParam;
import com.sun.image.codec.jpeg.JPEGImageEncoder;
/**
 * @author Madarco
 *
 */
public class JpgResizer {

	private int maxThumbWidth;
	private int maxThumbHeight;
	private int quality;

	/**
	 * @param thumbWidth
	 * @param thumbHeight
	 * @param quality
	 */
	public JpgResizer(int thumbWidth, int thumbHeight, int quality) {
		this.maxThumbWidth = thumbWidth;
		this.maxThumbHeight = thumbHeight;
		this.quality = quality;
	}

	public BufferedInputStream resizeImage(String imageFile) throws IOException, InterruptedException {
		//Load image:
		Image image = Toolkit.getDefaultToolkit().getImage(imageFile);
		MediaTracker mediaTracker = new MediaTracker(new Container());
	    mediaTracker.addImage(image, 0);
	    mediaTracker.waitForID(0);

	    double thumbRatio = (double)maxThumbWidth / (double)maxThumbHeight;
	    int imageWidth = image.getWidth(null);
	    int imageHeight = image.getHeight(null);
	    int thumbHeight = maxThumbHeight;
	    int thumbWidth = maxThumbWidth;

	    double imageRatio = (double)imageWidth / (double)imageHeight;
	    if (thumbRatio < imageRatio) {
	      thumbHeight = (int)(maxThumbWidth / imageRatio);
	    } else {
	      thumbWidth = (int)(maxThumbHeight * imageRatio);
	    }

	    System.out.println(imageFile + " h:" + thumbHeight + " w:" + thumbWidth + " img h:" + imageHeight + " w: " + imageWidth);

	    // draw original image to thumbnail image object and
	    // scale it to the new size on-the-fly
	    BufferedImage thumbImage = new BufferedImage(thumbWidth,
	      thumbHeight, BufferedImage.TYPE_INT_RGB);
	    Graphics2D graphics2D = thumbImage.createGraphics();
	    graphics2D.setRenderingHint(RenderingHints.KEY_INTERPOLATION,
	      RenderingHints.VALUE_INTERPOLATION_BILINEAR);
	    graphics2D.drawImage(image, 0, 0, thumbWidth, thumbHeight, null);

	    //Save the thumb as JPG:
	    File file = File.createTempFile("uploadApplet", "img"); //$NON-NLS-1$ //$NON-NLS-2$
	    BufferedOutputStream out = new BufferedOutputStream(new
	    	      FileOutputStream(file));
	    JPEGImageEncoder encoder = JPEGCodec.createJPEGEncoder(out);
	    JPEGEncodeParam param = encoder.
	      getDefaultJPEGEncodeParam(thumbImage);
	    quality = Math.max(0, Math.min(quality, 100));
	    param.setQuality((float)quality / 100.0f, false);
	    encoder.setJPEGEncodeParam(param);
	    encoder.encode(thumbImage);
	    out.close();

	    return new BufferedInputStream(new FileInputStream(file));
	}

	/**
	 * @return the quality
	 */
	public int getQuality() {
		return this.quality;
	}

	/**
	 * @param quality the quality to set
	 */
	public void setQuality(int quality) {
		this.quality = quality;
	}

	/**
	 * @return the thumbHeight
	 */
	public int getThumbHeight() {
		return this.maxThumbHeight;
	}

	/**
	 * @param thumbHeight the thumbHeight to set
	 */
	public void setThumbHeight(int thumbHeight) {
		this.maxThumbHeight = thumbHeight;
	}

	/**
	 * @return the thumbWidth
	 */
	public int getThumbWidth() {
		return this.maxThumbWidth;
	}

	/**
	 * @param thumbWidth the thumbWidth to set
	 */
	public void setThumbWidth(int thumbWidth) {
		this.maxThumbWidth = thumbWidth;
	}

}
