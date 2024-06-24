<template>
  <div class="container">
    <div
      v-if="loading"
      class="d-flex justify-content-center align-content-center"
    >
      <div class="spinner-grow mt-5"></div>
    </div>

    <div v-else>
      <h1 class="text-center mt-5">{{ title }}</h1>

      <div v-if="error">
        <p class="text-danger">{{ error.message }}</p>
        <p
          v-for="err in error.errors"
          class="text-danger"
        >
          {{ err[0] }}
        </p>
      </div>

      <form
        v-if="point"
        @submit.prevent="onSubmit"
      >
        <div class="form-group row">
          <label
            for="pointName"
            class="col-2 col-form-label"
            >Name</label
          >
          <div class="col">
            <input
              v-model="point.name"
              id="pointName"
              class="form-control mb-2"
            />
          </div>
        </div>
        <div class="form-group row">
          <label
            for="pointX"
            class="col-2 col-form-label"
            >X Value</label
          >
          <div class="col">
            <input
              v-model="point.x"
              onkeydown="return ['Backspace','Delete','ArrowLeft','ArrowRight', 'Tab'].includes(event.code) ||
                                (event.key === '-' && !event.target.value.includes('-')) 
                                ? true 
                                : !isNaN(Number(event.key)) && event.code !== 'Space'"
              id="pointX"
              class="form-control mb-2"
            />
          </div>
        </div>
        <div class="form-group row">
          <label
            for="pointY"
            class="col-2 col-form-label"
            >Y Value</label
          >
          <div class="col">
            <input
              v-model="point.y"
              onkeydown="return ['Backspace','Delete','ArrowLeft','ArrowRight', 'Tab'].includes(event.code) ||
                                (event.key === '-' && !event.target.value.includes('-')) 
                                ? true 
                                : !isNaN(Number(event.key)) && event.code !== 'Space'"
              id="pointY"
              class="form-control mb-2"
            />
          </div>
        </div>

        <div style="display: flexbox">
          <button
            type="submit"
            class="btn btn-primary"
            :disabled="!formIsUpdated || dataInvalid"
          >
            Save
          </button>

          <button
            type="button"
            v-if="enableDelete"
            v-on:click="deletePoint"
            class="btn btn-danger"
          >
            Delete
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
            v-on:click="onReset"
            :disabled="!formIsUpdated"
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
export default {
  props: {
    title: String,
    loading: Boolean,
    error: Object,
    point: Object,
    originalPoint: Object,
    onSubmit: Function,
    onReset: Function,
    enableDelete: Boolean,
  },
  data() {
    return {
      otherPoints: null,
    };
  },
  computed: {
    dataInvalid: function () {
      if (!this.otherPoints) {
        return true;
      }
      if (!this.point) {
        return true;
      }
      let nameIsTaken = false;
      this.otherPoints.forEach((other) => {
        if (this.point.name === other.name) {
          nameIsTaken = true;
        }
      });
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
        this.point.name === null ||
        this.point.name === "" ||
        nameIsTaken
      );
    },
    formIsUpdated: function () {
      if (!this.point) {
        return false;
      }
      return (
        (this.point && this.point.x !== this.originalPoint.x) ||
        this.point.y !== this.originalPoint.y ||
        this.point.name !== this.originalPoint.name
      );
    },
  },
  methods: {
    async deletePoint() {
      if (!confirm("Are you sure you want to delete this point?")) {
        return;
      }
      this.loading = true;
      this.error = null;
      axios
        .delete(`http://localhost:8000/api/points/${this.point.id}`)
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
  },
};
</script>

<style scoped>
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Remove arrows for Firefox */
input[type="number"] {
  -moz-appearance: textfield;
  appearance: textfield;
}
</style>
