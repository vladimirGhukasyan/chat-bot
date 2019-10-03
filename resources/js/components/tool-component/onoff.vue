<template>
    <div @click="data = !data" class="onoff-tool-component">
        <div v-show="data" style="display: flex;">
            <svg style="left: 20px;position: relative;align-self: center;" width="2" height="15" viewBox="0 0 2 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="2" height="15" fill="white"/>
            </svg>
            <div style="position: relative;left: 35px;align-self: center;background: #529E37;color: #000000;width: 56px;height: 44px;text-align: center;line-height: 44px;border-radius: 32px;">ON</div>
        </div>
        <div v-show="!data" style="display: flex;">
            <div style="position: relative;left: 7px;align-self: center;background: #a91607;color: #000000;width: 56px;height: 44px;text-align: center;line-height: 44px;border-radius: 32px;">OFF</div>
            <svg style="left: 20px;position: relative;align-self: center;" width="2" height="15" viewBox="0 0 2 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="2" height="15" fill="white"/>
            </svg>
        </div>
    </div>
</template>

<script>
    export default {
        name: "onoff",
        computed: {

        },
        mounted() {

        },
        methods: {

        },
        data() {
            return {
                data: false
            }
        },
    }
</script>

<style scoped lang="scss">
    .onoff-tool-component {
        cursor: pointer;display: flex;width: 100px;height: 56px;background: #2B3135;border-radius: 28px;
    }
</style>
