<template>
    <div>
       <div class="content">
			<div class="container-fluid">

				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Create Blog <Button @click="addModal=true"><Icon type="md-add" /> Add Blog</Button></p>
                    <div class="input_field">
                        <Input type="text" name="title" v-model="data.title" placeholder="title"/>
                    </div>
					<div class="_overflow _table_div blog_editor">
                        <editor
                            ref="editor"
                            autofocus
							:config="config"
                            :init-data="initData"
                            @save ="onSave"
                        />
					</div>
                    <div class="input_field">
                        <Input type="textarea" name="post_excerpt" v-model="data.post_excerpt" :rows="4" placeholder="Post excerpt" />
                    </div>
                    <div class="input_field">
                        <Select name="category_id" placeholder="Select category" v-model="data.category_id" filterable multiple>
                            <Option v-for="(c,i) in category" :value="c.id" :key="i">{{ c.categoryName }}</Option>
                        </Select>
                    </div>
                    <div class="input_field">
                        <Select name="tag_id" placeholder="Select tag" v-model="data.tag_id" filterable multiple>
                            <Option v-for="(t,i) in tag" :value="t.id" :key="i">{{ t.tagName }}</Option>
                        </Select>
                    </div>
                    <div class="input_field">
                        <Input name="metaDescription" type="textarea" v-model="data.metaDescription" :rows="4" placeholder="Meta description" />
                    </div>
                    <div class="input_field">
                        <Button @click="save" :loading="isCreating" :disabled="isCreating">{{isCreating ? 'Please wait..' : 'Create blog' }}</Button>
                    </div>
				</div>
			</div>
		</div>
    </div>
</template>

<script>
export default {
	data(){
		return {
            config: {
                tools: {
                    header: require('@editorjs/header'),
                    list: require('@editorjs/list'),
                    raw: require('@editorjs/raw'),
                    quote: require('@editorjs/quote'),
                    image: require('@editorjs/image'),
                    delimiter: require('@editorjs/delimiter'),
                    code: require('@editorjs/code'),
                    link: require('@editorjs/link'),
					inlinecode: require('@editorjs/inline-code'),
                    table: require('@editorjs/table'),
                    checklist: require('@editorjs/checklist'),
                    embed: require('@editorjs/embed'),
                    },
                image: {
                    endpoints: {
                        byFile: 'http://localhost:8008/uploadFile',
                        byUrl: 'http://localhost:8008/fetchUrl',
                    },
                    field: 'image',
                    types: 'image/*'
                }
            },
            initData: null,

			data: {
                title : '',
				post : '',
				post_excerpt : '',
				metaDescription : '',
				category_id : [],
				tag_id : [],
				jsonData: null
			},
            articleHTML: '',
			category : [],
			tag : [],
			isCreating: false,
		}
	},
	methods: {
		async add() {
			if(this.data.roleName.trim()=='') return this.e('Role Nameis required!')
			const res = await this.callApi('post','app/create_role', this.data)
			if(res.status === 201){
				this.roles.unshift(res.data)
				this.s('Role has been added successfully!')
				this.addModal = false
				this.data.roleName = ''
			}else{
				if(res.status == 422){
					this.e(res.data.errors.roleName[0])
				} else{

					this.swr()

				}
			}
		},
        async onSave(response){
            console.log('afaf')
            var data = response
            console.log('dldksfjlksdjfds', data)
            await this.outputHtml(data.blocks)

        },
		async save(){
            this.$refs.editor._data.state.editor.save().then(async(response) => {
                var data = response;
                await this.outputHtml(data.blocks)
                this.data.post = this.articleHTML
                this.data.jsonData = JSON.stringify(data)
                this.isCreating = true
                const res = await this.callApi('post', 'app/create_blog', this.data)
                console.log('Post data is ', this.data)
                if(res.status == 200){
                    this.s('Blog post has been created successfully')
                    // redirect
                    this.$router.push('/blogs')
                }else {
                    this.swr()
                }

                this.isCreating = false
            })
        },
        outputHtml(articleObj){
            console.log('article blog',articleObj)
		    articleObj.map(obj => {
				switch (obj.type) {
				case 'paragraph':
					this.articleHTML += this.makeParagraph(obj);
					break;
				case 'image':
					this.articleHTML += this.makeImage(obj);
					break;
				case 'header':
					this.articleHTML += this.makeHeader(obj);
					break;
				case 'raw':
					this.articleHTML += `<div class="ce-block">
					<div class="ce-block__content">
					<div class="ce-code">
						<code>${obj.data.html}</code>
					</div>
					</div>
				</div>\n`;
					break;
				case 'code':
					this.articleHTML += this.makeCode(obj);
					break;
				case 'list':
					this.articleHTML += this.makeList(obj)
					break;
				case "quote":
					this.articleHTML += this.makeQuote(obj)
					break;
				case "warning":
					this.articleHTML += this.makeWarning(obj)
					break;
				case "checklist":
					this.articleHTML += this.makeChecklist(obj)
					break;
				case "embed":
					this.articleHTML += this.makeEmbed(obj)
					break;


				case 'delimeter':
					this.articleHTML += this.makeDelimeter(obj);
					break;
				default:
					return '';
				}
			});
		},
	},
    async created(){
            const [cat,tag] = await Promise.all([
                this.callApi('get','app/get_category'),
                this.callApi('get','app/get_tag')

            ])
            if(cat.status == 200 ){
                this.category = cat.data
                this.tag = tag.data
            } else {
                this.swr()
            }
    },
}
</script>
<style>
    .blog_editor{
            border-radius: 4px;
            border: 1px solid black;
            padding: 4px 7px;
            width: 716px;
            margin-left: 160px;
            color: rgb(14, 8, 1);
            font-size: 14px;
            background-image: none;
            background-color: #fafaf7;
            z-index: -1;
    }
    .blog_editor:hover{
        border-radius: 1px solid rgb(57, 169, 221);
    }
    .input_field{
        width: 717px;
        margin: 20px 0 20PX 160px;
    }
</style>

