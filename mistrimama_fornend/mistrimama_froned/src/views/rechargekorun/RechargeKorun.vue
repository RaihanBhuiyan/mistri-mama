<template>
    <v-container fluid style="">
        <v-card>
            <v-card-title class="white--text" style="background-color: #febe00;">
                <h5><v-icon>flash_on</v-icon>রিচার্জ করুন</h5>
            </v-card-title>
            <v-layout wrap>
                <v-flex md6>
                    <v-card-text class="title font-weight-bold">এম.এফ.এস / ব্যাংক</v-card-text>
                    <v-form @submit.prevent style="padding: 16px;">
                        <v-select color="accent" item-text="name" item-value="value" :items="itemsMedium" v-model="medium" :error-messages="display_errors.medium" label="মাধ্যম নির্বাচন করুন" @change="changeInstructions()"></v-select>
                        <v-text-field v-if="bkashShow" color="accent" clearable v-model="transactionNumber" :error-messages="display_errors.trxno" label="ট্রানজেকশন নাম্বার"></v-text-field>
                        <v-text-field v-if="bankShow" color="accent" clearable v-model="branchNumber" :error-messages="display_errors.branch_number" label="ব্রাঞ্চের নাম"></v-text-field>
                        <v-text-field v-if="bankShow" color="accent" clearable v-model="serialNumber" :error-messages="display_errors.serial_number" label="সিরিয়াল নাম্বার"></v-text-field>
                        
                        <v-menu v-if="bankShow" v-model="datePicker" :close-on-content-click="false" :nudge-right="40" lazy transition="scale-transition" offset-y full-width min-width="290px">
                            <template v-slot:activator="{ on }">
                                <v-text-field v-model="date" label="তারিখ" :error-messages="display_errors.date" readonly v-on="on"></v-text-field>
                            </template>
                            <v-date-picker v-model="date" @input="datePicker = false"></v-date-picker>
                        </v-menu>

                        <v-text-field v-if="mistrimamaAgentShow" color="accent" clearable v-model="memoNumber" :error-messages="display_errors.memo_number" label="রশিদ নং"></v-text-field>
                        <v-text-field v-if="mistrimamaAgentShow" color="accent" clearable v-model="agentIdNumber" :error-messages="display_errors.agent_id_number" label="এজেন্ট আই ডি"></v-text-field>
                        <v-text-field color="accent" clearable v-model="amount" :error-messages="display_errors.amount" label="টাকার পরিমাণ"></v-text-field>
                        <v-btn @click="recharge" type="submit" color="primary">নিশ্চিত করুন</v-btn>
                    </v-form>
                </v-flex>
                <v-flex md6>
                    <v-card-text class="title font-weight-bold">{{ propInstruction.title }}</v-card-text>
                    <div v-for="instruction in propInstruction.instructions" :key="instruction" class="label-divs">{{ instruction }}</div>
                </v-flex>
            </v-layout>
        </v-card>
        <v-snackbar v-model="snackbar" :vertical="true" :bottom="'bottom'" :right="'right'" :timeout="10000">
            {{ alertMessage }}
            <v-btn class="snackButton" color="primary" flat @click="snackbar = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
    import {
        mapState
    }
    from "vuex";
    import axios from "../../axios_instance";

    export default {
        data() {
            return {
                date: new Date().toISOString().substr(0, 10),
                datePicker: false,
                snackbar: false,
                alertMessage: null,
                transactionNumber: null,
                branchNumber: null,
                serialNumber: null,
                date: null,
                memoNumber: null,
                agentIdNumber: null,
                amount: null,
                medium: 'bkash',
                bkashShow: true,
                bankShow: false,
                mistrimamaAgentShow: false,
                itemsMedium: [{
                    name: "বিকাশ",
                    value: "bkash"
                }, {
                    name: "ব্যাংক ডিপোজিট",
                    value: "Bank Deposit"
                }, {
                    name: "মিস্ত্রিমামা এজেন্ট ডিপোজিট",
                    value: "Mistrimama Agent"
                }],
                propInstruction: {
                    title: null,
                    instructions: []
                },
                mfsInstruction: {
                    'bkash': {
                        title: 'বিকাশ এর মাধ্যমে মিস্ত্রি মামা একাউন্ট-এ রিচার্জ করার জন্য নিচের ধাপগুলো অনুসরণ করুন -',
                        instructions: [
                            "ধাপ-১: আপনার পার্সোনাল বিকাশ নাম্বার থেকে মিস্ত্রি মামা মার্চেন্ট নাম্বার ০১৭২৭০৬৩৫৯৩ এ প্রয়োজন অনুযায়ী টাকা সেন্ড করুন।",
                            "ধাপ-২: টাকা রিচার্জ করার পর আপনার মোবাইল নাম্বার-এ বিকাশ থেকে একটি কনফার্মেশন এসএমএস আসবে তা সংরক্ষন করুন।",
                            "ধাপ-৩: যেকোন স্মার্ট ডিভাইস থেকে আপনার আইডি এবং পাসওয়ার্ড দিয়ে মিস্ত্রি মামা একাউন্ট-এ লগইন করুন।",
                            "ধাপ-৪: আপনার ড্যাশবোর্ড-এর বাম পাশের মেনু অপশন থেকে রিচার্জ করুন বাটন-টি সিলেক্ট করুন।",
                            "ধাপ-৫: এরপর স্ক্রিন-এ একটি বক্স দেখা যাবে যেখানে মাধ্যম (বিকাশ), ট্রানজেক্শন আইডি (সংরক্ষিত বিকাশ-এর এসএমএস থেকে পাওয়া যাবে) এবং টাকার পরিমান (বিকাশ এর দ্বারা সেন্ডকৃত টাকার পরিমান) পূরণ করে নিশ্চিতকরুন বাটন-টি সিলেক্ট করুন।",
                            "ধাপ-৬: প্রদানকৃত তথ্যগুলো সঠিক হলে আপনার স্ক্রিন-এ একটি নোটিফিকেশন চলে আসবে এবং মিস্ত্রি মামা আপনার রিচার্জ রিকোয়েস্ট-টি একসেপ্ট করলে ৩৬ ঘন্টার মধ্যে আপনার মোবাইল নাম্বার-এ একটি এসএমএস চলে আসবে যা আপনি মিস্ত্রি মামা অনলাইন ব্যালেন্স চেক করেও দেখতে পারবেন।"
                        ]
                    },
                    'bank deposit': {
                        title: 'ব্যাংক ডিপোজিট-এর মাধ্যমে মিস্ত্রি মামা একাউন্ট-এ রিচার্জ করার জন্য নিচের ধাপগুলো অনুসরণ করুন -',
                        instructions: [
                            "ধাপ-১: ব্র্যাক ব্যাংক এর যেকোন ব্রাঞ্চ থেকে মিস্ত্রি মামা ব্যাংক আকাউন্ট ১৫৩২২০৪২৩৮৫৪৯০০১ এ প্রয়োজন অনুযায়ী টাকা জমা দিন।",
                            "ধাপ-২: টাকা জমা দেবার পর জমা স্লিপ-টি সংরক্ষন করুন।",
                            "ধাপ-৩: যেকোন স্মার্ট ডিভাইস থেকে আপনার আইডি এবং পাসওয়ার্ড দিয়ে মিস্ত্রি মামা একাউন্ট-এ লগইন করুন।",
                            "ধাপ-৪: আপনার ড্যাশবোর্ড-এর বাম পাশের মেনু অপশন থেকে রিচার্জ করুন বাটন-টি সিলেক্ট করুন।",
                            "ধাপ-৫: এরপর স্ক্রিন-এ একটি বক্স দেখা যাবে যেখানে মাধ্যম (ব্যাংক ডিপোজিট), ব্রাঞ্চের নাম ও তারিখ (সংরক্ষিত ডিপোজিট স্লিপ থেকে পাওয়া যাবে) এবং টাকার পরিমান (জমা টাকার পরিমান) পূরণ করে নিশ্চিতকরুন বাটন-টি সিলেক্ট করুন।",
                            "ধাপ-৬: প্রদানকৃত তথ্যগুলো সঠিক হলে আপনার স্ক্রিন-এ একটি নোটিফিকেশন চলে আসবে এবং মিস্ত্রি মামা আপনার রিচার্জ রিকোয়েস্ট-টি একসেপ্ট করলে ৩৬ ঘন্টার মধ্যে আপনার মোবাইল নাম্বার-এ একটি এসএমএস চলে আসবে যা আপনি মিস্ত্রি মামা অনলাইন ব্যালেন্স চেক করেও দেখতে পারবেন।"
                        ]
                    },
                    'mistrimama agent': {
                        title: 'মিস্ত্রি মামা এজেন্ট ডিপোজিট-এর মাধ্যমে মিস্ত্রি মামা একাউন্ট-এ রিচার্জ করার জন্য নিচের ধাপগুলো অনুসরণ করুন -',
                        instructions: [
                            "ধাপ-১: কোনো প্রকার ঝামেলা ছাড়া মিস্ত্রি মামা এজেন্ট-এর নিকট প্রয়োজন অনুযায়ী টাকা জমা দিন।",
                            "ধাপ-২: টাকা জমা দেবার পর জমা রশিদ গ্রহণ করুন এবং তা সংরক্ষন করুন।",
                            "ধাপ-৩: যেকোন স্মার্ট ডিভাইস থেকে আপনার আইডি এবং পাসওয়ার্ড দিয়ে মিস্ত্রি মামা একাউন্ট-এ লগইন করুন।",
                            "ধাপ-৪: আপনার ড্যাশবোর্ড-এর বাম পাশের মেনু অপশন থেকে রিচার্জ করুন বাটন-টি সিলেক্ট করুন।",
                            "ধাপ-৫: এরপর স্ক্রিন-এ একটি বক্স দেখা যাবে যেখানে মাধ্যম (মিস্ত্রি মামা এজেন্ট), রশিদ নং ও এজেন্ট আইডি (সংরক্ষিত রশিদ থেকে পাওয়া যাবে) এবং টাকার পরিমান (জমা টাকার পরিমান) পূরণ করে নিশ্চিতকরুন বাটন-টি সিলেক্ট করুন।",
                            "ধাপ-৬: প্রদানকৃত তথ্যগুলো সঠিক হলে আপনার স্ক্রিন-এ একটি নোটিফিকেশন চলে আসবে এবং মিস্ত্রি মামা আপনার রিচার্জ রিকোয়েস্ট-টি একসেপ্ট করলে ৩৬ ঘন্টার মধ্যে আপনার মোবাইল নাম্বার-এ একটি এসএমএস চলে আসবে যা আপনি মিস্ত্রি মামা অনলাইন ব্যালেন্স চেক করেও দেখতে পারবেন।"
                        ]
                    },
                },
                display_errors: [],
            };
        },
        methods: {
            async recharge() {
                this.display_errors = [];
                try {
                    let response = await axios.post("/recharge-request", {
                        trxno: this.transactionNumber,
                        branch_number: this.branchNumber,
                        serial_number: this.serialNumber,
                        date: this.date,
                        memo_number: this.memoNumber,
                        agent_id_number: this.agentIdNumber,
                        amount: this.amount,
                        medium: this.medium
                    });
                    this.alertMessage = response.data.message;
                    this.snackbar = true;
                    
                    
                    this.transactionNumber = null;                 
                    this.branchNumber = null;
                    this.serialNumber = null;
                    this.date = null;
                    this.memo_number = null;
                    this.agent_id_number = null;
                    this.amount = null;
                    this.medium = 'bkash';
                } catch (error) {
                    if(error.response.data.errors){
                        this.display_errors = error.response.data.errors;
                    }
                    this.alertMessage = error.response.data.message;
                    this.snackbar = true;
                }
            },
            changeInstructions()
            {
                var d = this.medium.toLowerCase();
                this.propInstruction.title = this.mfsInstruction[d].title;
                this.propInstruction.instructions = this.mfsInstruction[d].instructions;

                this.bkashShow = false;
                this.bankShow = false;
                this.mistrimamaAgentShow = false;
                if(d == 'bkash')
                {
                    this.bkashShow = true;
                }
                else if(d == 'bank deposit')
                {
                    this.bankShow = true;
                }
                else if(d == 'mistrimama agent')
                {
                    this.mistrimamaAgentShow = true;
                }
            }
        },
        created() { 
            this.changeInstructions();
        },
        watch: {},
        computed: {}
    };
</script>
<style  scoped>
.label-divs {
    margin: 15px;
}
</style>