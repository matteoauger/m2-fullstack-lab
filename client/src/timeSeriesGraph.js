import React from 'react';
import * as d3 from 'd3';

class timeSeriesGraph extends React.Component {
    componentDidMount() {
      this.drawChart();
    }
      
    drawChart() {
      const data = [12, 5, 6, 6, 9, 10];
      const width = 500;
      const height = 500;
      
      const svg = d3.select("body")
        .append("svg")
        .attr("width", width)
        .attr("height", height)
        .style("margin-left", 100);

      // Create scale
      var x_scale = d3.scaleLinear()
        .domain([0, data.length])
        .range([0, width - 100]);

      var y_scale = d3.scaleLinear()
        .domain([0, d3.max(data)])
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

      var points = [
        [0, 80],
        [100, 100],
        [200, 30],
        [300, 50],
        [400, 40],
        [500, 80]
      ];

      data.forEach(function(element, index) {
        points[index] = [index*100, height/2 - ((height/(2*d3.max(data)))*element)];
      });

      var pathData = lineGenerator(points);

      d3.select('path')
        .attr('d', pathData);
    }
          
    render(){
      return <div id={"#" + this.props.id}></div>
    }
  }
      
  export default timeSeriesGraph;