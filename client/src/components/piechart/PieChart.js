import React from 'react';
import * as d3 from 'd3';
import { fetch } from '../../utils/dataAccess';

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
    d3.select("svg").remove()
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
    const options = {
      method: 'GET',
      mode: 'cors',
      cache: 'default' 
    };
    fetch(`land_value_claims/salesrepartition?year=${encodeURIComponent(this.state.year)}`, options)
      .then((response) => {
        response.json().then((data) => {
          this.setState({
            data: data
          })
        })
      })
  }

  drawChart() {
    const width = 1000;
    const height = 450;
    const margin = 40;
    const holeSize = 100;
    const MIN_SALE = 5;

    const radius = Math.min(width, height) / 2 - margin;
    // preparing the data 
    // setting to "Autre" every record with sales lesser than the MIN_SALE threshold
    const data = this.state.data.filter((d) => d.sales.toFixed(0) > MIN_SALE);
    const sales = 100 - data.reduce((d1, d2) => d1 + d2.sales || 0, 0);

    if (sales && sales > .5)
      data.push({stateName: "Autre", sales});

    const svg = d3.select("#piechart")
    .append("svg")
      .attr("width", width)
      .attr("height", height)
      .attr("id","svg")
    .append("g")
      .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

    const tooltip = d3.select("#labels")
      .append("div")
      .attr("class", "tooltip")
      .style("opacity", 0);

    const color = d3.scaleOrdinal()
      .domain(data)
      .range(["#6dd3ce", "#8b2635", "#c8e9a0", "#f7a278", "#9d5c63", 
              "#46351d", "#53d8fb", "#ff101f", "#fa8334", "#820263",
              "#32de8a", "#d90368"]);

    const pie = d3.pie()
      .value(function(d) {
        return d.sales;
      });

      // The arc generator
    const arc = d3.arc()
      .innerRadius(radius * 0.5)         // This is the size of the donut hole
      .outerRadius(radius * 0.8);

    // Another arc that won't be drawn. Just for labels positioning
    const outerArc = d3.arc()
      .innerRadius(radius * 0.9)
      .outerRadius(radius * 0.9);

    svg
      .selectAll('whatever')
      .data(pie(data))
      .enter()
      .append('path')
      .attr('d', d3.arc()
        .innerRadius(holeSize)    
        .outerRadius(radius)
      )
      .attr('fill', function(d){ return(color(d.data.sales)) })
      .attr("stroke", "black")
      .style("stroke-width", "2px")
      .style("opacity", 0.7)
      .on("mouseover", function(d, i) {
        d3.select(this).style("opacity", 0.9);
        tooltip.html(d.data.stateName + ": " + d.data.sales.toFixed(2) + " %")
        .style("font-size", "20px")
        .style("left", margin + "px")	
        tooltip.style("opacity", 1);
    })
    .on("mouseout", function(d) {
        d3.select(this).style("opacity", 0.7);
        tooltip.style("opacity", 0);
    });

    // Add the polylines between chart and labels:
svg
  .selectAll('allPolylines')
  .data(pie(data))
  .enter()
  .append('polyline')
    .attr("stroke", "black")
    .style("fill", "none")
    .attr("stroke-width", 1)
    .attr('points', function(d) {
      var posA = arc.centroid(d) // line insertion in the slice
      var posB = outerArc.centroid(d) // line break: we use the other arc generator that has been built only for that
      var posC = outerArc.centroid(d); // Label position = almost the same as posB
      var midangle = d.startAngle + (d.endAngle - d.startAngle) / 2 // we need the angle to see if the X position will be at the extreme right or extreme left
      posC[0] = radius * 0.95 * (midangle < Math.PI ? 1 : -1); // multiply by 1 or -1 to put it on the right or on the left
      return [posA, posB, posC]
    })

// Add the polylines between chart and labels:
svg
  .selectAll('allLabels')
  .data(pie(data))
  .enter()
  .append('text')
  .text( function(d) {  return `${d.data.stateName} (${d.data.sales.toFixed(0)} %)` } )
    .attr('transform', function(d) {
        var pos = outerArc.centroid(d);
        var midangle = d.startAngle + (d.endAngle - d.startAngle) / 2
        pos[0] = radius * 0.99 * (midangle < Math.PI ? 1 : -1);
        return 'translate(' + pos + ')';
    })
    .style('text-anchor', function(d) {
        var midangle = d.startAngle + (d.endAngle - d.startAngle) / 2
        return (midangle < Math.PI ? 'start' : 'end')
    })

    
  }

  render(){
    return (
      <div>
        <h2>Répartition des ventes par région</h2>
        <select value={this.state.year} onChange={this.changeYear}>
          <option value="2015">2015</option>
          <option value="2016">2016</option>
          <option value="2017">2017</option>
          <option value="2018">2018</option>
          <option value="2019">2019</option>
        </select>
        <div id="piechart"></div>
        <div id="labels"></div>
      </div>
    )
  }
}

      
export default PieChart;