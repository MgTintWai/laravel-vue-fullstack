<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <h1>I will show all other components react to changes</h1>
                <h2>Current state of counter is:{{ getCounter }} </h2>
            </div>
            <div>
                <comA></comA>
            </div>
            <Button type="info" @click="changeCounter">Change of the State of Counter</Button>
        </div>
    </div>
</template>

<script>
import comA from './comA.vue'
import {mapGetters, mapActions} from 'vuex'
export default {
    computed : {
        ...mapGetters(['getCounter'])
    },
    methods: {
        ...mapActions(['changeCounterAction'])
    },
    methods: {
        changeCounter(){
            this.$store.dispatch('changeCounterAction', 1)
            // this.$store.commit('changeTheCounter', 1)
        },
        runTest(){
            console.log('This is testing')
        }
    },
    components: {
        comA,
    },
    watch: {
        getCounter(value){
            console.log('Counter is changing ', value)
            this.runTest()
        }
    }
}
</script>