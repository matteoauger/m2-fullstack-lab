import React from 'react';
import * as d3 from 'd3';
import { fetch } from './utils/dataAccess';

class Barchart extends React.Component {
    constructor(props) {
        super(props)

        this.fetchData = this.fetchData.bind(this)
        this.changeInterval = this.changeInterval.bind(this)

        this.state = ({
            data: [],
            interval: "month"
        })
    }

    componentDidMount() {
      this.fetchData();
    }

    componentDidUpdate(prevProps, prevState) {
        if(prevState.interval !== this.state.interval) {
            this.fetchData()
        }
        d3.select("#svg").remove()
        if(prevState.data !== this.state.data) {
            this.drawChart()
        }
    }

    changeInterval(event) {
        this.setState({
            interval: event.target.value
        })
    }

    fetchData() {
        const myInit = { method: 'GET',
                   mode: 'cors',
                   cache: 'default' };
        fetch(`land_value_claims/salesbyinterval?interval=${this.state.interval}&date_start=2015-01-01&date_end=2015-01-10`, myInit)
          .then((response) => {
            response.json().then((data) => {
              this.setState({
                data
              })
            })
          })
      }

    drawChart() {
        const data = this.state.data;
        const nbData = data.length;
        const maxData = d3.max(data, (d) => { return d.sales_count });
        const width = 1000;
        const height = 500;
        const barWidth = (width-50)/nbData;

        const svg = d3.select("#barchart")
            .append("svg")
            .attr("width", width)
            .attr("height", height)
            .style("margin-left", 100);
            
        const tooltip = d3.select("#barchart")
            .append("div")
            .attr("class", "tooltip")
            .style("opacity", 0);

        const y_scale = d3.scaleLinear()
            .domain([0, maxData])
            .range([height-25, 0]);

        const y_axis = d3.axisLeft().scale(y_scale);

        svg.append("g")
            .attr("transform", "translate(50, 20)")
            .call(y_axis);

        svg.selectAll("rect")
            .data(data)
            .enter()
            .append("rect")
            .attr("x", (d, i) => 52+i * barWidth)
            .attr("y", (d, i) => height - (d.sales_count/maxData)*(height-20))
            .attr("width", barWidth - 1)
            .attr("height", (d, i) => (d.sales_count/maxData)*(height-20) - 3)
            .attr("fill", "#0066cc")
            .on("mouseover", function(d) {
                d3.select(this).attr("fill", "#0080ff");
                tooltip.html(d.current_date + ": " + d.sales_count + " ventes")
                    .style("font-size", "20px")
                    .style("left", (d3.event.pageX) + "px")		
                    .style("top", (d3.event.pageY - 28) + "px");
                tooltip.style("opacity", 1);
            })
            .on("mouseout", function(d) {
                d3.select(this).attr("fill", "#0066cc");
                tooltip.style("opacity", 0);
            });
    }

    render() {
        return <body>
            <title>Nombre de ventes</title>
            <select value={this.state.interval} onChange={this.changeInterval}>
                <option value="day">Jour</option>
                <option value="month">Mois</option>
                <option value="year">Année</option>
            </select>
            <input type="date" name="startDate"></input>
            <input type="date" name="endDate"></input>
            <div id="barchart"></div>
        </body>
    }
};

export default Barchart;
