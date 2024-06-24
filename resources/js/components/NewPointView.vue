<template>
  <div class="container">
    <div
      v-if="loading"
      class="d-flex justify-content-center align-content-center"
    >
      <div class="spinner-grow mt-5"></div>
    </div>

    <div v-else>
      <h1 class="text-center mt-5">Create Point</h1>

      <div v-if="error">
        <p class="text-danger">{{ error.message }}</p>
        <p
          v-for="err in error.errors"
          class="text-danger"
        >
          {{ err[0] }}
        </p>
      </div>

      <form @submit.prevent="createPoint">
        <div style="display: flex; flex-direction: column">
          <label
            for="pointName"
            class="form-label"
            >Name</label
          >
          <input
            v-model="point.name"
            placeholder="Enter a name for the new point"
            class="form-control"
            id="pointName"
          />
          <br />
          <label
            for="pointX"
            class="form-label"
            >X Value</label
          >
          <input
            v-model="point.x"
            type="number"
            placeholder="new point x value"
            class="form-control"
            id="pointX"
          />
          <br />
          <label
            for="pointY"
            class="form-label"
            >Y Value</label
          >
          <input
            v-model="point.y"
            type="number"
            placeholder="new point y value"
            class="form-control"
            id="pointY"
          />
        </div>

        <div style="display: flexbox; margin-top: 8px">
          <button
            type="submit"
            :disabled="!formIsUpdated || dataInvalid"
            class="btn btn-primary"
          >
            Save
          </button>

          <button
            type="button"
            v-on:click="cancel"
            style="float: right; margin-left: 4px"
            class="btn btn-dark"
          >
            Cancel
          </button>

          <button
            type="button"
            :disabled="!formIsUpdated"
            v-on:click="reset"
            style="float: right"
            class="btn btn-success"
          >
            Reset
          </button>
        </div>
      </form>

      <relative-points
        :point="point"
        v-on:loaded-other-points="otherPoints = $event"
      ></relative-points>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      point: { name: null, x: null, y: null },
      otherPoints: null,
      loading: false,
      error: null,
    };
  },
  computed: {
    dataInvalid: function () {
      if (!this.otherPoints) {
        return true;
      }
      let nameIsTaken = false;
      this.otherPoints.forEach((other) => {
        if (this.point.name === other.name) {
          nameIsTaken = true;
        }
      });
      console.log(Number.isInteger(Number(this.point.x)));
      return (
        this.point === null ||
        this.point.x === null ||
        this.point.y === null ||
        this.point.x === "" ||
        this.point.y === "" ||
        isNaN(Number(this.point.x)) ||
        isNaN(Number(this.point.y)) ||
        !Number.isInteger(Number(this.point.x)) ||
        !Number.isInteger(Number(this.point.y)) ||
        this.point.name === "" ||
        nameIsTaken
      );
    },
    formIsUpdated: function () {
      return this.point.name || this.point.x || this.point.y;
    },
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
    cancel() {
      this.$router.push("/");
    },
    reset() {
      this.point = { name: null, x: null, y: null };
    },
  },
};
</script>
