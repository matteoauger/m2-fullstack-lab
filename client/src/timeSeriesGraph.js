import React from 'react';
import * as d3 from 'd3';

class timeSeriesGraph extends React.Component {
    componentDidMount() {
      this.drawChart();
    }
      
    drawChart() {
      //const data = [12, 5, 6, 6, 9, 10];
      const data = {
        2015: [
          10, 10, 10, 10, 10, 10, 10, 10, 10, 19, 20, 21
        ],
        2016: [
          10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21
        ],
        2017: [
          10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21
        ],
        2018: [
          10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21
        ],
        2019: [
          10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21
        ]
      }
      
      const width = 1000;
      const height = 1000;
      const numberElements = 60
      
      const svg = d3.select("body")
        .append("svg")
        .attr("width", width)
        .attr("height", height)
        .style("margin-left", 100);

      // Create scale
      var x_scale = d3.scaleLinear()
        .domain([2015, 2020])
        .range([0, width - 100]);

      var highestY = 25;
      var y_scale = d3.scaleLinear()
        .domain([0, highestY])
        .range([(height/2), 0]);
              
      // Add scales to axis
      var x_axis = d3.axisBottom()
        .scale(x_scale);

      var y_axis = d3.axisLeft()
        .scale(y_scale);

      //Append group and insert axis
      svg.append("g")
        .attr("transform", "translate(50, 10)")
        .call(y_axis);

      svg.append("g")
        .attr("transform", "translate(50, " + (height/2 + 10)+ ")")
        .call(x_axis)

      var lineGenerator = d3.line();

      var points = [];


      var cpt = 0;
      for (let [key, value] of Object.entries(data)) {
        value.forEach(function(element, index) {
          points[cpt++] = [cpt*((width-100)/numberElements), height/2 - ((height/(2*highestY))*element)];
        });
      }

      var pathData = lineGenerator(points);

      d3.select('path')
        .attr('d', pathData)
        .style("stroke", "#0297db");
    }
          
    render(){
      return (
        <div>
          <h1>Prix moyen du m2</h1>
          <div id={"#" + this.props.id}></div>
        </div>
      )
    }
  }
      
  export default timeSeriesGraph;