<template>
      <point-form 
        title="New Point" 
        :loading="loading" 
        :error="error" 
        :point="point" 
        :originalPoint="{ name: null, x: null, y: null }"
        :onSubmit="createPoint" 
        :onReset="reset"
        :enableDelete="false"
      ></point-form>
</template>
<script>
import axios from "axios";

export default {
  data() {
    return {
      point: { name: null, x: null, y: null },
      loading: false,
      error: null,
    };
  },
  methods: {
    async createPoint() {
      this.loading = true;
      this.error = null;
      axios
        .post(`http://localhost:8000/api/points`, this.point)
        .catch((err) => {
          this.error = err.response.data;
        })
        .finally(() => {
          this.loading = false;
          if (this.error === null) {
            this.$router.push("/");
          } 
        });
    },
    reset() {
      console.log(this.point)
      this.point = { name: null, x: null, y: null };
      console.log(this.point)
    },
  },
};
</script>
