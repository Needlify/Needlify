<template>
    <div>
        <button @click="dec">-</button>
        <span>{{ count }}</span>
        <button @click="inc">+</button>
    </div>
</template>

<script>
export default {
    tag: "my-counter",
    name: "MyCounter",
    data: () => ({
        count: 0,
    }),
    methods: {
        inc() {
            this.count++;
        },
        dec() {
            this.count--;
        },
    },
};
</script>

<style scoped>
span,
button {
    font-size: 200%;
}

span {
    width: 4rem;
    display: inline-block;
    text-align: center;
}

button {
    width: 4rem;
    height: 4rem;
    border: none;
    border-radius: 10px;
    background-color: seagreen;
    color: white;
}
</style>
