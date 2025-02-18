<template>
  <div>
    <div class="img-container">
      <img ref="image" :src="src">
    </div>
    <img class="img-prev" :src="destination">
  </div>
</template>

<script>
import Cropper from "cropperjs";
export default {
  name: "ImageCropper",
  props: {
    src: String
  },
  data() {
    return {
      image: {},
      cropper: {},
      destination: {}
    };
  },
  mounted() {
    this.image = this.$refs.image;
    this.cropper = new Cropper(this.image, {
      zoomable: false,
      scalable: false,
      aspectRatio: 1,
      crop: () => {
        const canvas = this.cropper.getCroppedCanvas();
        this.destination = canvas.toDataURL("image/png");
        console.log(this.destination);
        this.$emit("clicked", this.destination);
      }
    });
  }
};
</script>
<style  scoped>
.img-container {
  width: 400px;
  height: 400px;
}
.img-prev {
  margin-top: 30px;
  width: 200px;
  height: 200px;
}
</style>
