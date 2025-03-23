import React from "react";

const Image = ({ src, alt, className }) => {
  return (
    <img
      src={`http://127.0.0.1:8000/image/${src}`}
      alt={alt}
      className={`${className}`}
    />
  );
};

export default Image;
