
<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';

export default {
    setup() {
        const data = ref([]);
        const loading = ref(true);
        const error = ref(null);

        const fetchData = async () => {
            try {
                const response = await axios.get("http://localhost:8000/api/points");
                data.value = response.data;
                console.log(data);
            } catch (err) {
                error.value = 'Failed to fetch data';
            } finally {
                loading.value = false;
            }
        };

        onMounted(() => {
            fetchData();
        });

        return {
            data,
            loading,
            error,
        };
    },
};
</script>

<template>
    <div class="container">
        <div v-if="loading">Loading...</div>
        <div v-else-if="error">{{ error }}</div>
        <div v-else>
            <h1>Points</h1>
            <ul>
                <li v-for="point in data" :key="point.id">{{ point.name }}: {{ point.x }}, {{ point.y }}</li>
            </ul>
        </div>
    </div>
</template>

