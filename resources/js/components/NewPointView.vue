<template>
      <point-form 
        title="New Point" 
        :loading="loading" 
        :error="error" 
        :point="point" 
        :originalPoint="{ name: '', x: '', y: '' }"
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
      point: { name: '', x: '', y: '' },
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
      this.point = { name: null, x: null, y: null };
    },
  },
};
</script>
