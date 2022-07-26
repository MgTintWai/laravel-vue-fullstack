import Vue from 'vue'
import Vuex from 'vuex';

Vue.use(Vuex)

const store =  new Vuex.Store({
    state : {
        counter: 1000,
        deleteModalObj: {
            showDeleteModal:false,
            deleteUrl: '',
            data: null,
            deletingIndex: -1,
            isDeleted: false
        },
        tags : [],
        user: false,
        userPermisson: null
    },
    getters: {
        getCounter(state){
            return state.counter
        },
        getDeleteModalObj(state){
            return state.deleteModalObj
        },
        getUserPermission(state){
            return state.userPermisson
        }
    },
    mutations: {
        changeTheCounter(state,data){
            state.counter += data
        },
        setDeleteModal(state,data){
            const deleteModalObj = {
                showDeleteModal:false,
                deleteUrl: '',
                data: null,
                deletingIndex: -1,
                isDeleted: data
            }
            state.deleteModalObj = deleteModalObj
        },
        setDeletingModalObj(state,data){
            state.deleteModalObj = data
        },
        setUpdateUser(state,data){
            state.user = data
        },
        setUserPermission(state,data){
            state.userPermisson = data
        }
    },
    actions : {
        changeCounterAction({commit}, data){
            commit('changeTheCounter', data)
        }
    }
})

export default store;
