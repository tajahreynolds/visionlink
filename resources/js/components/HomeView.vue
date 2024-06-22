<template>
  <div class="container">
    <div v-if="loading">Loading...</div>
    <div v-else-if="error">{{ error }}</div>
    <div
      v-else
      id="points"
    >
      <h1>Points</h1>
      <ul>
        <li
          v-for="point in points"
          :key="point.id"
        >
          <router-link :to="{ name: 'EditPoint', params: { id: point.id } }">
            {{ point.name }}: {{ point.x }}, {{ point.y }}
          </router-link>
        </li>
      </ul>
      <button v-on:click="gotoNewPointView">New</button>
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
      try {
        const response = await axios.get("http://localhost:8000/api/points");
        this.points = response.data;
        this.error = null;
      } catch (err) {
        this.error = "Failed to fetch data";
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
#points > h1 {
  text-align: center;
  margin-top: 24px;
}
</style>
