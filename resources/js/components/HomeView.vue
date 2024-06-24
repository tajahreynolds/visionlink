<template>
  <div class="container">
    <div v-if="loading" class="d-flex justify-content-center align-content-center">
      <div class="spinner-grow mt-5"></div>
    </div>
    <div v-else-if="error" class="mt-5 text-center text-danger">{{ error }}</div>
    <div v-else>
      <h1 class="mt-5 text-center">Points</h1>
      <points-table v-if="points.length > 0" :points="points" :editable="true"></points-table>
      <h2 v-else class="text-center">There are no points in the database.</h2>
      <button v-on:click="gotoNewPointView" class="btn btn-primary">New</button>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      points: [],
      loading: true,
      error: null,
    };
  },
  methods: {
    async fetchData() {
      this.loading = true;
      this.error = null;
      try {
        const response = await axios.get("http://localhost:8000/api/points");
        this.points = response.data;
      } catch (err) {
        this.error = err.response.data.message;
      } finally {
        this.loading = false;
      }
    },
    gotoNewPointView() {
      this.$router.push('/newPoint');
    },
  },
  mounted() {
    this.fetchData();
  },
};
</script>

<style scoped>
button {
  margin: 8px 0 0 10%;
}
</style>
