<template>
    <div @click="changeStatus();" class="checkbox-component">
        <svg v-if="data" width="11" height="6" viewBox="0 0 11 6" fill="none" xmlns="http://www.w3.org/2000/svg" style="position: relative;top: -2px;left: 2px;">
            <path d="M10.6791 0.651248C10.7535 0.57683 10.7907 0.483807 10.7907 0.381481C10.7907 0.279155 10.7535 0.186132 10.6791 0.111713C10.5302 -0.0371237 10.2884 -0.0371237 10.1395 0.111713L5.39535 4.8559L0.651163 0.111713C0.502326 -0.0371237 0.260465 -0.0371237 0.111628 0.111713C-0.0372093 0.26055 -0.0372093 0.502411 0.111628 0.651248L5.12558 5.6745C5.27442 5.82334 5.51628 5.82334 5.66512 5.6745L10.6791 0.651248Z" fill="white"/>
        </svg>
    </div>
</template>

<script>
    export default {
        name: "checkbox",
        computed: {

        },
        mounted() {

        },
        methods: {
            changeStatus() {
                this.data = !this.data;
                this.$emit('data', this.data);
            }
        },
        data() {
            return {
                data: false
            }
        },
    }
</script>

<style scoped lang="scss">
    .checkbox-component {
        cursor: pointer;
        background: #2B3135;
        border-radius: 4px;
        width: 16px;
        height: 16px;
    }
</style>
