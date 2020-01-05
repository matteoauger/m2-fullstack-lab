import React from 'react';
import * as d3 from 'd3';

class PieChart extends React.Component {
  componentDidMount() {
    this.drawChart()
  }

  drawChart() {
    const width = 500;
    const height = 500;
    const margin = 100;
    const holeSize = 100;

    var radius = Math.min(width, height) / 2 - margin;

    const data = {
      "Bretagne": "10",
      "Nouvelle Aquitaine": "20",
      "Normandie": "5",
      "Ile de France": "50",
      "Occitanie": "15"
    }

    // append the svg object to the div called 'my_dataviz'
    var svg = d3.select("body")
    .append("svg")
      .attr("width", width)
      .attr("height", height)
    .append("g")
      .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

    const tooltip = d3.select("body")
      .append("div")
      .attr("class", "tooltip")
      .style("opacity", 0);

    var color = d3.scaleOrdinal()
      .domain(data)
      .range(["#98abc5", "#8a89a6", "#7b6888", "#6b486b", "#a05d56"]);

    var pie = d3.pie()
      .value(function(d) {return d.value; })
    var data_ready = pie(d3.entries(data))

    // Build the pie chart: Basically, each part of the pie is a path that we build using the arc function.
    svg
      .selectAll('whatever')
      .data(data_ready)
      .enter()
      .append('path')
      .attr('d', d3.arc()
        .innerRadius(holeSize)         // This is the size of the donut hole
        .outerRadius(radius)
      )
      .attr('fill', function(d){ return(color(d.data.key)) })
      .attr("stroke", "black")
      .style("stroke-width", "2px")
      .style("opacity", 0.7)
      .on("mouseover", function(d) {
        d3.select(this).style("opacity", 0.9);
        tooltip.html(d.stateName + ": " + d.sales)
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
        <div id={"#" + this.props.id}></div>
      </div>
    )
  }
}

      
export default PieChart;