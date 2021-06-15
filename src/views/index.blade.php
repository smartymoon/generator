<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <!-- 引入组件库 -->
    <script src="https://unpkg.com/element-ui/lib/index.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.15/lodash.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <title>代码生成器</title>
</head>

<body class="bg-gray-300">
<div id="app" class="container flex mx-auto bg-white h-full py-8 px-4">
    <el-dialog title="结果" :visible.sync="dialogVisible">
        <div v-html="response"></div>
    </el-dialog>

    <div class="w-1/5 h-full">
        <el-form ref="form" :model="config" label-position="top" size="small">
            <el-form-item label="模板" class="w-4/5">
                <el-radio v-model="config.template" label="api"></el-radio>
                <el-radio v-model="config.template" label="inertia"></el-radio>
                <el-radio v-model="config.template" label="blade_view"></el-radio>
            </el-form-item>

            <el-form-item label="模型名" class="w-4/5">
                <el-input v-model="config.model" placeholder="首字母大写" />
            </el-form-item>

            <el-form-item label="hasMany(写 Model 名)" class="w-4/5">
                <el-select v-model="config.hasMany" multiple allow-create filterable default-first-option placeholder="可写多个" class="block"></el-select>
            </el-form-item>

            <el-form-item label="模块名" class="w-4/5">
                <el-select v-model="config.module" filterable  allow-create default-first-option clearable class="block">
                    <el-option v-for="(item, key) in modules" :key="key" :label="item" :value="item" default ></el-option>
                </el-select>
            </el-form-item>

            <el-form-item label="Seed Times" class="w-4/5">
                <el-input-number v-model="config.seed_times" :step="20"></el-input-number>
            </el-form-item>


            <el-form-item label="生成文件">
                <el-checkbox-group v-model="config.to_create_files">
                    <el-checkbox label="repository"></el-checkbox>
                    <el-checkbox label="vue"></el-checkbox>
                    <el-checkbox label="test"></el-checkbox>
                    <el-checkbox label="controller"></el-checkbox>
                </el-checkbox-group>
            </el-form-item>

        </el-form>
        <div class="pt-10 fixed right-0 bottom-0 pr-40 pb-32">
            <div class="mb-2">
                <el-button type="danger" size="small" @click="dropTable">删表</el-button>
            </div>
            <div class="mb-2">
                <el-button type="danger" size="small" @click="removeStorage">清缓</el-button>
            </div>
            <div>
                <el-button type="primary" size="small" @click="submit" :loading="loading">生成</el-button>
            </div>
        </div>
    </div>
    <div class="w-4/5 -mt-2">
        <el-form label-position="top" size="small" v-for="(field, index) in config.fields" :key="index" class="border p-4 mb-8 relative">
            <div class="absolute right-0 top-0 p-3">
                <div @click="deleteField(config.fields, index)">
                    <i class="el-icon-delete text-lg"></i>
                </div>
            </div>
            <div class="flex">
                <el-form-item label="字段名" class="w-1/3">
                    <el-input v-model="field.field_name" placeholder="字段名" />
                </el-form-item>
                <el-form-item label="说明 Comment" class="w-1/3 px-4">
                    <el-input v-model="field.comment" />
                </el-form-item>
                <el-form-item label="默认 Default" class="w-1/3 px-4">
                    <el-input v-model="field.default" />
                </el-form-item>
            </div>
            <div class="flex">
                <el-form-item label="字段类型" class="w-1/3 mr-3">
                    <el-select v-model="field.type" filterable class="w-full">
                        <el-option value="id"></el-option>
                        <el-option value="unsignedTinyInteger"></el-option>
                        <el-option value="unsignedSmallInteger"></el-option>
                        <el-option value="unsignedInteger"></el-option>
                        <el-option value="unsignedMediumInteger"></el-option>
                        <el-option value="unsignedBigInteger"></el-option>
                        <el-option value="unsignedDecimal"></el-option>
                        <el-option value="char(10)"></el-option>
                        <el-option value="string"></el-option>
                        <el-option value="text"></el-option>
                        <el-option value="boolean"></el-option>
                        <el-option value="json"></el-option>
                        <el-option value="year"></el-option>
                        <el-option value="date"></el-option>
                        <el-option value="dateTime"></el-option>
                        <el-option value="ipAddress"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="Faker" class="w-1/3">
                    <el-select v-model="field.faker" filterable class="w-full" allow-create default-first-option>
                        <el-option-group label="自定义方法举例" disabled>
                            <el-option value="enum">enum('video','music', 'image')</el-option>
                            <el-option value="fixed">原样输出 fixed('精选')</el-option>
                        </el-option-group>
                        <el-option-group label="数字">
                            <el-option value="rand(0, 1)" label="Boolean"></el-option>
                            <el-option value="rand(1, 2)" label="2 数字枚选"></el-option>
                            <el-option value="rand(1, 3)" label="3 数字枚选"></el-option>
                            <el-option value="rand(1, 4)" label="4 数字枚选"></el-option>
                            <el-option value="rand(1, 5)" label="5 数字枚选"></el-option>
                            <el-option value="rand(1, 6)" label="6 数字枚选"></el-option>
                            <el-option value="rand(1, 10)" label="10内随机数"></el-option>
                            <el-option value="rand(1, 20)" label="1 - 20 随机数"></el-option>
                            <el-option value="rand(1, 50)" label="1 - 50 随机数"></el-option>
                            <el-option value="rand(1, 100)" label="1 - 100 随机数"></el-option>
                            <el-option value="rand(1, 500)" label="1 - 500 随机数"></el-option>
                            <el-option value="rand(10, 100)" label="10 - 100 随机数"></el-option>
                            <el-option value="rand(100, 900)" label="100 - 900 随机数"></el-option>
                            <el-option value="rand(1000, 9000)" label="1000 - 9000 随机数"></el-option>
                            <el-option value="rand(100000, 90000)" label="10000 - 90000 随机数"></el-option>
                        </el-option-group>
                        <el-option-group label="文字">
                            <el-option value="chinese(2)" label="2字"></el-option>
                            <el-option value="chinese(3)" label="3字"></el-option>
                            <el-option value="chinese(4)" label="4字"></el-option>
                            <el-option value="chinese(6)" label="6字"></el-option>
                            <el-option value="chinese(8)" label="8字"></el-option>
                            <el-option value="chinese(10)" label="10字"></el-option>
                            <el-option value="chinese(2, 4)" label="2 - 4字"></el-option>
                            <el-option value="chinese(10, 15)" label="10 - 15字"></el-option>
                            <el-option value="chinese(15, 20)" label="15 - 20字"></el-option>
                            <el-option value="chinese(80, 100)" label="段落"></el-option>
                            <el-option value="chinese(800, 1200)" label="文章"></el-option>
                        </el-option-group>
                        <el-option-group label="媒体">
                            <el-option value="image('square')" label="方图片"></el-option>
                            <el-option value="image('fat')" label="宽图片"></el-option>
                            <el-option value="image('tall')" label="高图片"></el-option>
                            <el-option value="video('fat')" label="宽视频"></el-option>
                            <el-option value="video('tall')" label="高视频"></el-option>
                        </el-option-group>
                        <el-option-group label="时间">
                            <el-option value="date('future')" label="未来"></el-option>
                            <el-option value="date('past')" label="过去"></el-option>
                        </el-option-group>
                        <el-option-group label="网址">
                            <el-option value="url()" label="URL"></el-option>
                        </el-option-group>
                        <el-option-group label="英文">
                            <el-option value="english_word()" label="英文单词"></el-option>
                        </el-option-group>
                    </el-select>
                </el-form-item>
            </div>
            <div>
                <div class="flex">
                    <el-form-item label="关联字段" class="mx-4">
                        <el-radio v-model="field.belongsTo" :label="true">是</el-radio>
                        <el-radio v-model="field.belongsTo" :label="false">否</el-radio>
                    </el-form-item>
                    <div class="w-2/3 pl-4">
                        <el-form-item label="特性">
                            <el-checkbox-group v-model="field.methods">
                                <el-checkbox label="nullable"></el-checkbox>
                                <el-checkbox label="index"></el-checkbox>
                                <el-checkbox label="unique"></el-checkbox>
                            </el-checkbox-group>
                        </el-form-item>

                        <!--
                        <div class="flex">
                            <el-form-item label="默认值" v-show="field.migration.includes('default')">
                                <el-input v-model="field.migration_params.default">
                            </el-form-item>

                            <el-form-item label="说明" v-show="field.migration.includes('comment')" class="ml-2">
                                <el-input v-model="field.migration_params.comment">
                            </el-form-item>
                        </div>
                        -->
                    </div>
                </div>
                <div>
                    <div class="flex mb-5">
                        <span class="align-middle">枚举</span>
                        <el-input v-model="field.enum.fileName" placeholder="文件名" class="w-32 pl-6" size="mini"></el-input>
                        <el-button size="mini" class="ml-2" @click="addEnum(field.enum.list)">加规则</el-button>
                    </div>
                    <div class="flex"  v-for="(myEnum,key) in field.enum.list" :key="key">
                        <el-form-item label="英文">
                            <el-input v-model="myEnum.english"></el-input>
                        </el-form-item>
                        <el-form-item label="中文" class="ml-4">
                            <el-input v-model="myEnum.chinese"></el-input>
                        </el-form-item>
                        <div @click="deleteEnum(field.enum.list, key)" class="pt-12 pl-4">
                            <i class="el-icon-delete text-lg"></i>
                        </div>
                    </div>
                </div>

            </div>
        </el-form>
        <div class="mt-8">
            <el-button size="mini" type="primary" class="float-right" @click="addField">新字段</el-button>
        </div>
    </div>
</div>
<script>
    const field_template = {
        field_name: '',
        belongsTo: false,
        type: 'string',
        methods: [],
        comment: '',
        default: '',
        faker: 'rand(1, 3)',
        enum: {
            fileName: '',
            list: []
        }
    };
    let app = new Vue({
        el: '#app',
        data: {
            modules: [],
            loading: false,
            dialogVisible: false,
            response: '',
            config: {
                template: 'api',
                model: '',
                hasMany: [],
                module: '',
                seed_times: 10,
                to_create_files: ['repository', 'controller', 'test', 'vue'],
                fields: [_.cloneDeep(field_template)]
            }
        },
        methods: {
            // type: success or error
            getModules() {
                axios.get('/lee/modules').then(({data}) => {
                    this.modules = data
                })
            },
            showResult(type, content) {
                this.$alert(content, '结果', {
                    type: type,
                    customClass: 'w-1/2 whitespace-pre-wrap',
                    confirmButtonText: '确定',
                });
            },
            submit() {
                this.loading = true
                axios.post('/lee', this.config).then(({ data }) => {
                    // todo error show
                    if (data.code === 201) {
                        this.showResult('success', '成功')
                    } else if (data.code === 204) {
                        this.showResult('warning', data.message)
                    }
                }).catch(err => {
                    const info = err.response.data
                    this.showResult('error',
                        `${info.exception}
${info.file}
${info.line} 行
${info.message}`
                    )
                }).finally(() => {
                    this.loading = false
                })
            },
            dropTable() {
                axios.post('drop-table', {
                    table: this.config.model
                }).then(() => {
                    this.$message.success('删除成功')
                }).catch(() => {
                    this.$message.error('失败')
                })
            },
            storageToBrowser() {
                localStorage.setItem('generator_config', JSON.stringify(this.config))
            },
            getConfigFromBrowser() {
                return JSON.parse(localStorage.getItem('generator_config'));
            },
            removeStorage() {
                localStorage.removeItem('generator_config');
                location.reload()
            },
            addField() {
                this.config.fields.push(_.cloneDeep(field_template))
            },
            addEnum(enums) {
                enums.push({
                    english: '',
                    chinese: '',
                })
            },
            deleteEnum(enums, key) {
                this.$delete(enums, key)
            },
            deleteField(fields, key) {
                this.$confirm('确认删除字段', '提示').then(() => {
                    this.$delete(fields, key)
                })
            }
        },
        mounted() {
            this.getModules()
            let data = this.getConfigFromBrowser()
            if (data) {
                this.config = data
            }
        },
        watch: {
            config: {
                handler() {
                    this.storageToBrowser()
                },
                deep: true
            }
        }
    })
</script>
</body>

</html>
