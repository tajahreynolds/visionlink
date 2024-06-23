<template>
  <div class="container">
    <div v-if="loading">Loading...</div>
    <div v-else-if="error">{{ error }}</div>
    <div
      v-else
      id="points"
    >
      <h1>Points</h1>
      <table>
        <thead>
          <tr>
            <th scope="col">Name</th>
            <th class="text-center" scope="col">X</th>
            <th class="text-center" scope="col">Y</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="point in points"
            :key="point.id"
          >
            <router-link :to="{ name: 'EditPoint', params: { id: point.id } }">
              <th scope="row">{{ point.name }}</th>
              <td class="text-center">{{ point.x }}</td>
              <td class="text-center">{{ point.y }}</td>
            </router-link>
          </tr>
        </tbody>
      </table>
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

button {
  margin: 8px 0 0 10%;
}

a {
  display: contents;
}

.text-center {
  text-align: center;
}

table {
  margin: auto;
  width: 80%;
  border-spacing: collapse;
  border: 2px solid rgb(140 140 140);
  font-family: sans-serif;
  font-size: 0.8rem;
  letter-spacing: 1px;
}

th,
td {
  border: 1px solid rgb(160 160 160);
  padding: 8px 10px;
}

tbody > tr:hover {
 background-color: lightgray
}

</style>
