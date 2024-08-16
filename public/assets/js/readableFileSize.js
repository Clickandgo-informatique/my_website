export function readableFileSize(attachmentSize) {
    const DEFAULT_SIZE = 0;
    const fileSize = attachmentSize ?? DEFAULT_SIZE;
  
    if (!fileSize) {
      return `${DEFAULT_SIZE} kb`;
    }
  
    const sizeInKb = fileSize / 1024;
  
    if (sizeInKb > 1024) {
      return `${(sizeInKb / 1024).toFixed(2)} mb`;
    } else {
      return `${sizeInKb.toFixed(2)} kb`;
    }
  }