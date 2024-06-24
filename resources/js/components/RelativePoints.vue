<template>
  <div style="margin-top: 24px">
    <div
      v-if="loading"
      class="d-flex justify-content-center align-content-center"
    >
      <div class="spinner-border mt-5"></div>
    </div>

    <div
      v-else-if="error"
      class="mt-5 text-center"
    >
        <p class="text-danger">{{ error.message }}</p>
        <p
          v-for="err in error.errors"
          class="text-danger"
        >
          {{ err[0] }}
        </p>
    </div>

    <div
      v-else-if="invalid"
      class="mt-5 text-center text-danger"
    >
      Can not calculate distance from the given point.
    </div>

    <div v-else-if="otherPoints.length > 0">
      <h2 class="text-center">
        Nearest Point<span v-if="relative.nearestPoints.length > 1">s</span> at
        distance {{ relative.minDistance }}
      </h2>
      <points-table
        :points="relative.nearestPoints"
        :editable="false"
      ></points-table>
      <br />

      <h2 class="text-center">
        Farthest Point<span v-if="relative.farthestPoints.length > 1">s</span>
        at distance {{ relative.maxDistance }}
      </h2>
      <points-table
        :points="relative.farthestPoints"
        :editable="false"
      ></points-table>
    </div>
    
    <div v-else>
      <h2 class="text-center">No Other Points Exist</h2>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    point: Object,
  },
  data() {
    return {
      otherPoints: [],
      loading: true,
      error: null,
    };
  },
  computed: {
    invalid: function () {
      return (
        this.point === null ||
        this.point.x === "" ||
        this.point.y === "" ||
        this.point.x === null ||
        this.point.y === null ||
        isNaN(Number(this.point.x)) ||
        isNaN(Number(this.point.y))
      );
    },
    relative: function () {
      let minDistance = Number.MAX_SAFE_INTEGER;
      let maxDistance = Number.MIN_SAFE_INTEGER;
      let nearestPoints = [];
      let farthestPoints = [];

      this.otherPoints.forEach((other) => {
        let distance = this.distanceBetween(this.point, other);

        if (distance < minDistance) {
          minDistance = distance;
          nearestPoints = [other]; // reset the array with the new nearest point
        } else if (distance === minDistance) {
          nearestPoints.push(other);
        }

        if (distance > maxDistance) {
          maxDistance = distance;
          farthestPoints = [other];
        } else if (distance === maxDistance) {
          farthestPoints.push(other);
        }
      });

      minDistance = Math.round(minDistance * 10) / 10;
      maxDistance = Math.round(maxDistance * 10) / 10;

      return {
        nearestPoints,
        minDistance,
        farthestPoints,
        maxDistance,
      };
    },
  },
  methods: {
    async fetchOtherPoints() {
      if (!this.point) {
        this.error = "Invalid Point";
        this.loading = false;
        return;
      }
      this.loading = true;
      this.error = null;
      axios
        .get(`http://localhost:8000/api/points`)
        .then((res) => {
          this.otherPoints = res.data.filter(
            (other) => other.id !== this.point.id
          );
        })
        .catch((err) => {
          this.error = err.response.data;
        })
        .finally(() => {
          this.loading = false;
          this.$emit('loaded-other-points', this.otherPoints);
        });
    },
    distanceBetween(point, other) {
      return Math.sqrt(
        Math.pow(other.x - point.x, 2) + Math.pow(other.y - point.y, 2)
      );
    },
  },
  mounted() {
    this.fetchOtherPoints();
  },
};
</script>
