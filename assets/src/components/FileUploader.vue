<template>
  <!--UPLOAD-->
  <div class="container">
    <label>
      Upload Your CSV
      <input
        type="file"
        class="file-upload"
        ref="fileUpload"
        v-on:change="stageFile()"
      />
    </label>
    <br />
    <!-- <button
      v-if="readyToSubmit"
      v-on:click="previewFile()"
      class="button button-primary"
    >
      View
    </button> -->
  </div>
</template>

<!-- Javascript -->
<script>
import { WpApi } from "@/services/WpApi";

export default {
  name: "FileUploader",
  props: ["filetype", "filedata"],
  data() {
    return {
      file: {},
      uploadId: {},
      readyToSubmit: false,
      leaveWarning: false
    };
  },
  beforeMount() {
    window.addEventListener("beforeunload", event => {
      if (!this.leaveWarning) return;
      event.preventDefault();
      // Chrome requires returnValue to be set.
      event.returnValue = "";
    });
  },
  methods: {
    /*
        Submits the file to the server
      */
    previewFile() {
      /*
        Initialize the form data with FormData API
      */
      let formData = new FormData();
      /*
        Add the form data we need to submit
      */
      formData.append("file", this.file);

      let $this = this;

      /*
        Make the request to the POST /single-file URL
      */
      WpApi.media()
        .file(this.file)
        .create({
          title: "CSV Upload",
          description: "Csv Upload for Import"
        })
        .then(function(response) {
          console.log(response);
          $this.$emit("uploaded", response.id);
        });
    },

    /*
        Handles a change on the file upload
      */
    stageFile() {
      this.leaveWarning = true;
      this.file = this.$refs.fileUpload.files[0];
      this.readyToSubmit = true;
      this.previewFile();
    }
  }
};
</script>
