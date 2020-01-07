import React from 'react';
import * as d3 from 'd3';
import { fetch } from '../../utils/dataAccess';
class Barchart extends React.Component {
    constructor(props) {
        super(props)
        this.fetchData = this.fetchData.bind(this)
        this.changeInterval = this.changeInterval.bind(this)
        this.changeStartDate = this.changeStartDate.bind(this)
        this.changeEndDate = this.changeEndDate.bind(this)
        this.state = ({
            data: [],
            interval: "month",
            startDate: "2015-01-01",
            endDate: "2016-01-01"
        })
    }
    componentDidMount() {
        this.fetchData();
    }
    componentDidUpdate(prevProps, prevState) {
        if(prevState.interval !== this.state.interval || prevState.startDate !== this.state.startDate || prevState.endDate !== this.state.endDate) {
            this.fetchData()
        }
        d3.select("svg").remove()
        if(prevState.data !== this.state.data) {
            this.drawChart()
        }
    }
    changeInterval(event) {
        this.setState({
            interval: event.target.value
        })
    }
    changeStartDate(event) {
        this.setState({
            startDate: event.target.value
        })
    }
    changeEndDate(event) {
        this.setState({
            endDate: event.target.value
        })
    }
    fetchData() {
        const options = {
            method: 'GET',
            mode: 'cors',
            cache: 'default' 
        };
        fetch(`land_value_claims/salesbyinterval?interval=${encodeURIComponent(this.state.interval)}&date_start=${encodeURIComponent(this.state.startDate)}&date_end=${encodeURIComponent(this.state.endDate)}`, options)
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
            .attr("id", "graph")
            .attr("width", width)
            .attr("height", height)
            .style("margin-left", 100);
            
        const tooltip = d3.select("#labels")
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
            .attr("fill", "#9d5c63")
            .on("mouseover", function(d, i) {
                d3.select(this).attr("fill", "#8b2635");
                tooltip.html(d.current_date.slice(0,10)+"<br>"+d.sales_count + " ventes")
                    .style("font-size", "20px")
                    .style("text-anchor", "middle")
                    .style("left", (52+ (i+1) * barWidth + barWidth/2) + "px")		
                tooltip.style("opacity", 1);
            })
            .on("mouseout", function(d) {
                d3.select(this).attr("fill", "#8b2635");
                tooltip.style("opacity", 0);
            });
    }
    render() {
        return <div>
            <title>Nombre de ventes</title>
            <select value={this.state.interval} onChange={this.changeInterval}>
                <option value="day">Jour</option>
                <option value="month">Mois</option>
                <option value="year">Année</option>
            </select>
            <input type="date" name="startDate" value={this.state.startDate} onChange={this.changeStartDate}></input>
            <input type="date" name="endDate" value={this.state.endDate} onChange={this.changeEndDate}></input>
            <div id="barchart"></div>
            <div id="labels"></div>
        </div>
    }
};
export default Barchart;