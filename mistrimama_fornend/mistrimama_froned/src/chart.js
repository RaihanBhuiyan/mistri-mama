import { Pie } from "vue-chartjs";

export default {
  extends: Pie,
  props: ["dailyData", "monthData", "dailyOrMonthly", "options"],
  mounted() {
    this.updateChartJs();
  },
  watch: {
    dailyOrMonthly: function() {
      this.updateChartJs();
    }
  },
  methods: {
    updateChartJs() {
      if (this.dailyOrMonthly === "daily") {
        this.renderChart(this.dailyData, this.options);
      } else {
        this.renderChart(this.monthData, this.options);
      }
    }
  }
};
