<script setup>
import Form from "./Form.vue";
defineProps({ product: Object });
</script>

<template>
    <Form :product="product" />
</template>
