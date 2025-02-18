<template>
    <v-card elevation="1">
        <v-card-title class="white--text" style="background-color: #febe00;">
            <h5><v-icon>swap_horizontal_circle</v-icon> নতুন লেনদেন</h5>
        </v-card-title>
        <v-card-actions>
            <v-flex sm12 md6>
                <v-dialog ref="startDateModal" v-model="startDateModal" :return-value.sync="start_date" persistent lazy full-width width="290px">
                    <template v-slot:activator="{ on }">
                        <v-text-field v-model="start_date" label="Start Date" prepend-icon="event" readonly v-on="on"></v-text-field>
                    </template>
                    <v-date-picker v-model="start_date" scrollable>
                        <v-spacer></v-spacer>
                        <v-btn flat color="primary" @click="startDateModal = false">Cancel</v-btn>
                        <v-btn flat color="primary" @click="$refs.startDateModal.save(start_date)">OK</v-btn>
                    </v-date-picker>
                </v-dialog>
            </v-flex>
            <v-flex sm12 md6>
                <v-dialog ref="endDateModal" v-model="endDateModal" :return-value.sync="end_date" persistent lazy full-width width="290px">
                    <template v-slot:activator="{ on }">
                        <v-text-field v-model="end_date" label="End Date" prepend-icon="event" readonly v-on="on"></v-text-field>
                    </template>
                    <v-date-picker v-model="end_date" scrollable>
                        <v-spacer></v-spacer>
                        <v-btn flat color="primary" @click="endDateModal = false">Cancel</v-btn>
                        <v-btn flat color="primary" @click="$refs.endDateModal.save(end_date)">OK</v-btn>
                    </v-date-picker>
                </v-dialog>
            </v-flex>
                <v-spacer></v-spacer>
            <v-btn color="primary" @click="ExportLendenBiboron()"><v-icon class="custom-icon">cloud_download</v-icon> </v-btn>
        </v-card-actions>
        <v-data-table v-if="statements.length != 0" :headers="headers" :items="statementsWithIndex">
            <template v-slot:items="props">
                <td class="text-xs-left">
                    <span  v-if="$i18n.locale=='en'">
                       {{ props.item.sl }}
                    </span>
                    <span  v-else> 
                        {{ e2btransform((props.item.sl).toString()) }}
                    </span>
                </td>
                <td class="text-xs-left">
                    <span  v-if="$i18n.locale=='en'">
                       {{ props.item.date }}
                    </span>
                    <span  v-else> 
                        {{ e2btransform(( props.item.date).toString()) }}
                    </span>
                </td>
                <td class="text-xs-left">{{ props.item.details }}</td>
                <td class="text-xs-left">{{props.item.trxno}}</td>
                <td class="text-xs-right">
                    <v-chip>
                        <v-avatar>
                            <v-icon :color="(props.item.status == 'credit' || props.item.status == 'income') ? 'success' : 'red'">{{ (props.item.status == 'credit' || props.item.status == 'income') ? 'add_circle_outline' : 'remove_circle_outline' }}</v-icon>
                        </v-avatar>
                        <span  v-if="$i18n.locale=='en'">
                           {{ props.item.amount }}
                        </span>
                        <span  v-else> 
                            {{ e2btransform((props.item.amount).toString()) }}
                        </span>
                        /-
                    </v-chip>
                </td>
            </template>
        </v-data-table>
        <v-card-text v-else style="text-align:center">There is no transaction here.</v-card-text>
    </v-card>
</template>

<script>
    import axios from "../../axios_instance";
    export default {
        props: {
            role: {
                type: String,
                default: ""
            }
        },
        data() {
            return {
                startDateModal: '',
                endDateModal: '',
                start_date: '',
                end_date: '',
                drawer: null,
                sideButtonShow: false,
                statements: [],
                sideData: [],
                headers: [{
                    text: "নং",
                    value: "sl"
                },{
                    text: "তারিখ",
                    value: "name"
                }, {
                    text: "বিস্তারিত",
                    value: "calories"
                }, {
                    text: "টি.এক্স.এন আইডি #",
                    value: "fat"
                }, {
                    text: "পরিমান",
                    value: "carbs",
                    align: 'right',
                }]
            };
        },
        methods: {
            async ExportLendenBiboron(){
                var d = new Date();
                let response = await axios.get('/sp/statements-history-lager-export');  
                let blob = new Blob([response.data], {type: 'text/csv'});
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'statement_'+d.getFullYear()+'-'+ (Number(d.getMonth())+1) + '-'+ d.getDate()+'.csv';
                link.click(); 
            },
            openDrawer: function(itemObject) {
                this.drawer = !this.drawer;
                this.sideData = itemObject;
            },
            showSideButtons: function() {
                this.sideButtonShow = true;
            },
            hideSideButtons: function() {
                this.sideButtonShow = false;
            },
            async getStatements() {
                try {
                    let loader = this.$loading.show();
                    var res = await axios.get("/sp/statements");
                    this.statements = res.data;
                    loader.hide();
                } catch (error) { 
                }
            }
        },
        created() {
            this.getStatements();
        },
        computed: {
            statementsWithIndex() {
                return this.statements.map((d, index) => ({ ...d, sl: index + 1 })).filter((order) => {
                    if(((this.start_date == '') || (this.end_date == '')))
                    {
                        return true;
                    }
                    else{
                        return (order.date >= this.start_date && order.date <= this.end_date);
                    }
                })
            },
        },
    };
</script>

<style  scoped>

</style>