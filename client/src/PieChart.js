import React from 'react';
import * as d3 from 'd3';
import { fetch } from './utils/dataAccess';

class PieChart extends React.Component {
  constructor(props) {
    super(props);

    this.fetchData = this.fetchData.bind(this);
    this.changeYear = this.changeYear.bind(this);

    this.state = {
      data: [],
      year: 2018
    }
  }

  componentDidMount() {
    this.fetchData()
  }

  componentDidUpdate(prevProps, prevState) {
    if(prevState.year !== this.state.year) {
      this.fetchData()
    }
    d3.select("#svg").remove()
    if(prevState.data !== this.state.data) {
      this.drawChart()
    }
  }

  changeYear(event) {
    this.setState({
      year: event.target.value
    })
    
  }

  fetchData() {
    var myInit = { method: 'GET',
               mode: 'cors',
               cache: 'default' };
    fetch(`land_value_claims/salesrepartition?year=${this.state.year}`, myInit)
      .then((response) => {
        response.json().then((data) => {
          console.log(data)
          this.setState({
            data: data
          })
        })
      })
  }

  drawChart() {
    const width = 500;
    const height = 500;
    const margin = 100;
    const holeSize = 100;

    var radius = Math.min(width, height) / 2 - margin;
    const data = this.state.data;

    console.log(data)

    var svg = d3.select("body")
    .append("svg")
      .attr("width", width)
      .attr("height", height)
      .attr("id","svg")
    .append("g")
      .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

    const tooltip = d3.select("body")
      .append("div")
      .attr("class", "tooltip")
      .style("opacity", 0);

    var color = d3.scaleOrdinal()
      .domain(data)
      .range(["#98abc5", "#8a89a6", "#7b6888", "#6b486b", "#a05d56"]);

    const pie = d3.pie()
      .value(function(d) {
        return d.sales;
      });
    // Build the pie chart: Basically, each part of the pie is a path that we build using the arc function.
    svg
      .selectAll('whatever')
      .data(pie(data))
      .enter()
      .append('path')
      .attr('d', d3.arc()
        .innerRadius(holeSize)         // This is the size of the donut hole
        .outerRadius(radius)
      )
      .attr('fill', function(d){ return(color(d.data.sales)) })
      .attr("stroke", "black")
      .style("stroke-width", "2px")
      .style("opacity", 0.7)
      .on("mouseover", function(d, i) {
        d3.select(this).style("opacity", 0.9);
        tooltip.html(d.data.stateName + ": " + d.data.sales)
        .style("font-size", "20px")
        .style("left", (d3.event.pageX) + "px")		
        .style("top", (d3.event.pageY - 28) + "px");
        tooltip.style("opacity", 1);
    })
    .on("mouseout", function(d) {
        d3.select(this).style("opacity", 0.7);
        tooltip.style("opacity", 0);
    });
  }

  render(){
    return (
      <div>
        <h1>Répartition des ventes par région</h1>
        <select value={this.state.year} onChange={this.changeYear}>
          <option value="2015">2015</option>
          <option value="2016">2016</option>
          <option value="2017">2017</option>
          <option value="2018">2018</option>
          <option value="2019">2019</option>
        </select>
        <div id={"#" + this.props.id}></div>
      </div>
    )
  }
}

      
export default PieChart;