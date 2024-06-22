<template>
  <div class="container">
    <div v-if="loading">Loading...</div>
    <div v-else>
      <h1>Edit Point</h1>
      <div v-if="error">{{ error }}</div>
      <form @submit.prevent="savePoint">
        <div style="display: flex; flex-direction: column">
          <input v-model="point.name" />
          <input v-model="point.x" />
          <input v-model="point.y" />
        </div>
        <div style="display: flexbox">
          <button type="submit">Save</button>
          <button type="button" v-on:click="deletePoint">Delete</button>
          <button type="button" v-on:click="cancel" style="float: right; margin-left: 4px">Cancel</button>
          <button type="reset" v-on:click="fetchPointData" style="float: right">Restore</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  props: {
    id: [String, Number],
  },
  data() {
    return {
      point: null,
      nearestPoints: [],
      farthestPoints: [],
      loading: true,
      error: null,
    };
  },
  methods: {
    async fetchPointData() {
      this.loading = true;
      this.error = null;
      axios
        .get(`http://localhost:8000/api/points/${this.id}`)
        .then((res) => {
          this.point = res.data.point;
        })
        .catch((err) => {
          console.error(err);
          this.error = "Failed to fetch data.";
        })
        .finally(() => {
          this.loading = false;
        });
    },
    async savePoint() {
      this.loading = true;
      this.error = null;
      axios
        .put(`http://localhost:8000/api/points/${this.id}`, this.point)
        .catch((err) => {
          console.error(err);
          this.error =
            "Failed to save point. Please check that you have provided valid data.";
        })
        .finally(() => {
          this.loading = false;
          this.error === null && this.$router.push("/");
        });
    },
    async deletePoint() {
      this.loading = true;
      this.error = null;
      axios
        .delete(`http://localhost:8000/api/points/${this.id}`)
        .then((res) => {
          console.log(res);
        })
        .catch((err) => {
          console.error(err);
          this.error = "Failed to delete point.";
        })
        .finally(() => {
          this.loading = false;
          this.error === null && this.$router.push("/");
        });
    },
    cancel() {
      this.$router.push("/");
    },
  },
  mounted() {
    this.fetchPointData();
  },
};
</script>
