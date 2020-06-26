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

        <div class="w-1/4 h-full">
            <el-form ref="form" :model="config" label-position="top" size="small">
                <el-form-item label="模型名" class="w-3/5">
                    <el-input v-model="config.model" placeholder="首字母大写">
                </el-form-item>

                <el-form-item label="hasMany(写 Model 名)" class="w-3/5">
                    <el-select v-model="config.hasMany" multiple allow-create filterable default-first-option placeholder="可写多个"></el-select>
                </el-form-item>

                <el-form-item label="Controller Namespace" class="w-3/5">
                    <el-select v-model="config.controller_namespace" filterable  allow-create default-first-option clearable>
                        <el-option value="Wap" default>Wap</el-option>
                    </el-select>
                </el-form-item>

                <el-form-item label="Seed Times" class="w-3/5">
                    <el-input-number v-model="config.seed_times" :step="20"></el-input-number>
                </el-form-item>


                <el-form-item label="生成文件">
                    <el-checkbox-group v-model="config.may_create_files">
                        <div>
                            <el-checkbox label="resource"></el-checkbox>
                            <el-checkbox label="repository"></el-checkbox>
                        </div>
                        <div>
                            <el-checkbox label="controller"></el-checkbox>
                            <el-checkbox label="request"></el-checkbox>
                        </div>
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
        <div class="w-3/4 full px-4 -mt-2">
            <el-form label-position="top" size="small" v-for="(field, index) in config.fields" :key="index" class="border p-4 mb-8 relative">
                <div class="absolute right-0 top-0 p-3">
                    <div @click="deleteField(config.fields, index)">
                        <i class="el-icon-delete text-lg"></i>
                    </div>
                </div>
                <div class="flex">
                    <el-form-item label="字段名" class="w-1/3">
                        <el-input v-model="field.field_name" placeholder="字段名">
                    </el-form-item>
                    <el-form-item label="关联字段？" class="w-1/3 ml-8">
                        <el-radio v-model="field.belongsTo" :label="true">是</el-radio>
                        <el-radio v-model="field.belongsTo" :label="false">否</el-radio>
                    </el-form-item>
                </div>
                <div class="flex">
                    <el-form-item label="字段类型" class="w-1/3">
                        <el-select v-model="field.type" filterable class="w-full">
                            <el-option value="increments"></el-option>
                            <el-option value="integerIncrements"></el-option>
                            <el-option value="tinyIncrements"></el-option>
                            <el-option value="smallIncrements"></el-option>
                            <el-option value="mediumIncrements"></el-option>
                            <el-option value="bigIncrements"></el-option>
                            <el-option value="char"></el-option>
                            <el-option value="string"></el-option>
                            <el-option value="text"></el-option>
                            <el-option value="mediumText"></el-option>
                            <el-option value="longText"></el-option>
                            <el-option value="integer"></el-option>
                            <el-option value="tinyInteger"></el-option>
                            <el-option value="smallInteger"></el-option>
                            <el-option value="mediumInteger"></el-option>
                            <el-option value="bigInteger"></el-option>
                            <el-option value="unsignedInteger"></el-option>
                            <el-option value="unsignedTinyInteger"></el-option>
                            <el-option value="unsignedSmallInteger"></el-option>
                            <el-option value="unsignedMediumInteger"></el-option>
                            <el-option value="unsignedBigInteger"></el-option>
                            <el-option value="float"></el-option>
                            <el-option value="double"></el-option>
                            <el-option value="decimal"></el-option>
                            <el-option value="unsignedDecimal"></el-option>
                            <el-option value="boolean"></el-option>
                            <el-option value="enum"></el-option>
                            <el-option value="set"></el-option>
                            <el-option value="json"></el-option>
                            <el-option value="jsonb"></el-option>
                            <el-option value="date"></el-option>
                            <el-option value="dateTime"></el-option>
                            <el-option value="dateTimeTz"></el-option>
                            <el-option value="time"></el-option>
                            <el-option value="timeTz"></el-option>
                            <el-option value="timestamp"></el-option>
                            <el-option value="timestampTz"></el-option>
                            <el-option value="timestamps"></el-option>
                            <el-option value="nullableTimestamps"></el-option>
                            <el-option value="timestampsTz"></el-option>
                            <el-option value="softDeletes"></el-option>
                            <el-option value="softDeletesTz"></el-option>
                            <el-option value="year"></el-option>
                            <el-option value="binary"></el-option>
                            <el-option value="uuid"></el-option>
                            <el-option value="ipAddress"></el-option>
                            <el-option value="macAddress"></el-option>
                            <el-option value="geometry"></el-option>
                            <el-option value="point"></el-option>
                            <el-option value="lineString"></el-option>
                            <el-option value="polygon"></el-option>
                            <el-option value="geometryCollection"></el-option>
                            <el-option value="multiPoint"></el-option>
                            <el-option value="multiLineString"></el-option>
                            <el-option value="multiPolygon"></el-option>
                            <el-option value="multiPolygonZ"></el-option>
                            <el-option value="computed"></el-option>
                            <el-option value="morphs"></el-option>
                            <el-option value="nullableMorphs"></el-option>
                            <el-option value="uuidMorphs"></el-option>
                            <el-option value="nullableUuidMorphs"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="外键" class="ml-12">
                        <el-radio v-model="field.foreign_policy" label="">无</el-radio>
                        <el-radio v-model="field.foreign_policy" label="cascade">cascade(都删)</el-radio>
                        <el-radio v-model="field.foreign_policy" label="restrict">restrict(禁删)</el-radio>
                    </el-form-item>
                    <el-form-item label="外键表" class="ml-8">
                        <el-input v-model="field.foreign_table" placeholder="表名" class="w-32" size="mini"></el-input>
                    </el-form-item>
                </div>
                <div>
                    <div class="flex">
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
                                    <el-option value="random_chinese(2)" label="2字"></el-option>
                                    <el-option value="random_chinese(3)" label="3字"></el-option>
                                    <el-option value="random_chinese(4)" label="4字"></el-option>
                                    <el-option value="random_chinese(6)" label="6字"></el-option>
                                    <el-option value="random_chinese(8)" label="8字"></el-option>
                                    <el-option value="random_chinese(10)" label="10字"></el-option>
                                    <el-option value="random_chinese(2, 4)" label="2 - 4字"></el-option>
                                    <el-option value="random_chinese(10, 15)" label="10 - 15字"></el-option>
                                    <el-option value="random_chinese(15, 20)" label="15 - 20字"></el-option>
                                    <el-option value="random_chinese(80, 100)" label="段落"></el-option>
                                    <el-option value="random_chinese(800, 1200)" label="文章"></el-option>
                                </el-option-group>
                                <el-option-group label="图片">
                                    <el-option value="random_image('square')" label="方图片"></el-option>
                                    <el-option value="random_image('fat')" label="宽图片"></el-option>
                                    <el-option value="random_image('tall')" label="高图片"></el-option>
                                </el-option-group>
                                <el-option-group label="时间">
                                    <el-option value="random_date('future')" label="未来"></el-option>
                                    <el-option value="random_date('past')" label="过去"></el-option>
                                </el-option-group>
                                <el-option-group label="网址">
                                    <el-option value="random_url()" label="URL"></el-option>
                                </el-option-group>
                                <el-option-group label="英文">
                                    <el-option value="english_word()" label="英文单词"></el-option>
                                </el-option-group>
                            </el-select>
                        </el-form-item>
                        <div class="w-2/3 pl-8">
                            <el-form-item label="特性">
                                <el-checkbox-group v-model="field.migration">
                                    <el-checkbox label="nullable"></el-checkbox>
                                    <el-checkbox label="default"></el-checkbox>
                                    <el-checkbox label="comment"></el-checkbox>
                                    <el-checkbox label="index"></el-checkbox>
                                    <el-checkbox label="unique"></el-checkbox>
                                </el-checkbox-group>
                            </el-form-item>

                            <div class="flex">
                                <el-form-item label="默认值" v-show="field.migration.includes('default')">
                                    <el-input v-model="field.migration_params.default">
                                </el-form-item>

                                <el-form-item label="说明" v-show="field.migration.includes('comment')" class="ml-2">
                                    <el-input v-model="field.migration_params.comment">
                                </el-form-item>
                            </div>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="w-1/2 border-r">
                            <h4> 
                                <span class="align-middle">验证</span>
                                <el-button size="mini" class="ml-2" @click="addRule(field.rules)">加规则</el-button>
                            </h4>
                            <div class="flex items-center" v-for="(rule,key) in field.rules" :key="key">
                                <el-form-item label="规则">
                                    <el-select v-model="rule.rule">
                                        <el-option value="required"></el-option>
                                        <el-option value="unique"></el-option>
                                    </el-select>
                                </el-form-item>
                                <el-form-item label="错误提示" class="mx-4">
                                    <el-input v-model="rule.message"></el-input>
                                </el-form-item>
                                <div @click="deleteRule(field.rules, key)" class="pt-6">
                                    <i class="el-icon-delete text-lg"></i>
                                </div>
                            </div>
                        </div>
                        <div class="w-1/2 pl-6">
                            <div class="flex">
                                <h4> <span class="align-middle">枚举</span>
                                    <el-button size="mini" class="ml-2" @click="addEnum(field.enum.list)">加规则</el-button>
                                </h4>
                                <el-input v-model="field.enum.fileName" placeholder="文件名" class="w-32 pl-6" size="mini"></el-input>
                            </div>
                            <div class="flex items-center" v-for="(myEnum,key) in field.enum.list" :key="key">
                                <el-form-item label="英文" class="mx-4">
                                    <el-input v-model="myEnum.english"></el-input>
                                </el-form-item>
                                <el-form-item label="中文" class="mr-4">
                                    <el-input v-model="myEnum.chinese"></el-input>
                                </el-form-item>
                                <div @click="deleteEnum(field.enum.list, key)" class="pt-6">
                                    <i class="el-icon-delete text-lg"></i>
                                </div>
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
            foreign_policy: '',
            foreign_table: '',
            migration: [],
            migration_params: {
                default: '',
                comment: ''
            },
            faker: 'rand(1, 3)',
            rules: [],
            enum: {
                fileName: '',
                list: []
            }
        };
        let app = new Vue({
            el: '#app',
            data: {
                loading: false,
                dialogVisible: false,
                response: '',
                config: {
                    model: '',
                    hasMany: [],
                    controller_namespace: 'Wap',
                    seed_times: 10,
                    may_create_files: ['resource', 'repository'],
                    fields: [_.cloneDeep(field_template)]
                }
            },
            methods: {
                // type: success or error
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
                addRule(rules) {
                    rules.push({
                        rule: '',
                        message: '输入有误'
                    })
                },
                addEnum(enums) {
                    enums.push({
                        english: '',
                        chinese: '',
                    })
                },
                deleteRule(rules, key) {
                    this.$delete(rules, key)
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
                $data = this.getConfigFromBrowser()
                if ($data) {
                    this.config = $data
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