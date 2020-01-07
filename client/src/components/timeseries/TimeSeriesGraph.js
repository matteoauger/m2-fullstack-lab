import React from 'react';
import * as d3 from 'd3';
import { fetch } from '../../utils/dataAccess';


class TimeSeriesGraph extends React.Component {
  constructor(props) {
    super(props)

    this.fetchData = this.fetchData.bind(this)

    this.state = {
      data: []
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

  fetchData() {
    const myInit = { method: 'GET',
               mode: 'cors',
               cache: 'default' };
    fetch(`land_value_claims/meanprices`, myInit)
      .then((response) => {
        response.json().then((data) => {
          this.setState({
            data
          })
        })
      })
  }
    
  drawChart() {
    let data = this.state.data;
    console.log(this.state.data)
    // Adapt data.
    data = data.map(({ current_date, mean }) => {
      const date = new Date(current_date);
      return { year: date.getFullYear(), month: date.getMonth() + 1, mean: +mean };
    });

    const width = 1000;
    const height = 1000;
    const minYear = d3.min(data, ({ year }) => year);
    const maxYear = d3.max(data, ({ year }) => year) + 1;
    const numberElements = (maxYear - minYear) * 12;
      
    const svg = d3.select("#graph")
      .append("svg")
      .attr("width", width)
      .attr("height", height)
      .style("margin-left", 100);

    // Create scale
    const x_scale = d3.scaleLinear()
      .domain([minYear, maxYear])
      .range([0, width - 100]);

    const highestY = d3.max(data, ({ mean }) => mean);
    const y_scale = d3.scaleLinear()
      .domain([0, highestY])
      .range([(height/2), 0]);
              
    // Add scales to axis
    const x_axis = d3.axisBottom()
      .tickArguments([6])
      .scale(x_scale)
      .tickFormat(d3.format("d"));

    const y_axis = d3.axisLeft()
      .scale(y_scale);

    //Append group and insert axis
    svg.append("g")
      .attr("transform", "translate(50, 10)")
      .call(y_axis);

    svg.append("g")
      .attr("transform", "translate(50, " + (height/2 + 10)+ ")")
      .call(x_axis)

    const lineGenerator = d3.line();

    // Prepreccess data year-month
    const preData = data.reduce((acc, {month, year, mean}) => {
      acc[year + '-' + month] = mean;
      return acc;
    }, {});

    // Calculate points
    const points = [];
    for (let year = minYear; year < maxYear; year++) {
      for (let month = 1; month < 13; month++) { 
        const mean = preData[year + '-' + month] || 0;
        const x = points.length * ((width - 100) / numberElements);
        const y = height / 2 - ((height / (2 * highestY)) * mean);
        points.push([x, y]);
      }
    }
    
    d3.select('path')
      .attr('d', lineGenerator(points))
      .style("stroke", "#8b2635");
  }
        
  render() {
    return (
      <div className="chart">
        <h2>Prix moyen du m2</h2>
        <div id='graph'></div>
      </div>
    )
  }
}
    
export default TimeSeriesGraph;