<template>
    <div>
        <h1>the number is : {{count}}</h1>
        <button @click="updatecount(1)">Increase</button>
        <button @click="updatecount(-1)">Decrease</button>
        <br>
        <br>

        <div v-for="(blogs, i) in blogs" :key="i">
            <h1>{{blogs.title}}</h1>
            <p>{{blogs.posts}}</p>
        </div>
        <div>
            <h1 v-if="showItem">show this item if "showItem" is true</h1>
            <h1 v-else>show this item if "showItem" is false</h1>
        </div>
        <button @click="changeItem() ">ChangeShowItem</button>
    </div>
</template>
<script>
export default {
    data(){
        return{
            count: 0,
            blogs: [],
            showItem: false
        }
    },
    methods: {
        updatecount(num){
        this.count += num
        },
        changeItem(){
            this.showItem = !this.showItem
        }
    },
    created(){
        let posts = [
            {title: 'this is the blog title 1', posts: 'this is the blog poss', id: 1},
            {title: 'this is the blog title 2', posts: 'this is the blog poss', id: 1},
            {title: 'this is the blog title 3', posts: 'this is the blog poss', id: 1}
        ]
        this.blogs = posts
    }
}
</script>