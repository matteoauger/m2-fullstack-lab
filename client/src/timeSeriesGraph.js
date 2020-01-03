import React from 'react';
import * as d3 from 'd3';

class timeSeriesGraph extends React.Component {
    componentDidMount() {
      this.drawChart();
    }
      
    drawChart() {
      //const data = [12, 5, 6, 6, 9, 10];
      const data = {
        "2015": {
          "1": "520.5011329600816",
          "2": "772.2204502052498",
          "3": "420.3294666899522",
          "4": "487.1929469414251",
          "5": "226.78182317562406",
          "6": "351.16317038605393",
          "7": "377.3673653642466",
          "8": "305.8121793503608",
          "9": "519.7324009288913",
          "10": "306.624099672393",
          "11": "296.8832992670402",
          "12": "353.2249665115454"
        },
        "2016": {
          "1": "531.4198743446956",
          "2": "397.12018975547096",
          "3": "498.7109405169448",
          "4": "601.7565868148987",
          "5": "767.6258834909405",
          "6": "946.9451955705846",
          "7": "931.3891706824817",
          "8": "452.6198246367302",
          "9": "292.63829221785977",
          "10": "345.13148095895804",
          "11": "346.7052334701728",
          "12": "385.47143864563594"
        },
        "2017": {
          "1": "680.8467733847177",
          "2": "251.6259119614332",
          "3": "369.8772476861033",
          "4": "638.7600064526473",
          "5": "334.12240015613486",
          "6": "388.79230401418715",
          "7": "334.66845459248225",
          "8": "323.23363955091565",
          "9": "337.4754299923526",
          "10": "448.93488277190124",
          "11": "340.2777735019563",
          "12": "431.36170229820266"
        },
        "2018": {
          "1": "659.232449850987",
          "2": "554.2838258170199",
          "3": "424.8378361645428",
          "4": "313.65955461464347",
          "5": "351.62408184657056",
          "6": "408.1349553284554",
          "7": "344.71705540194296",
          "8": "327.8777167243809",
          "9": "383.5143605779628",
          "10": "284.4881631718452",
          "12": "63.02395209580838"
        },
        "2019": {
          "1": "643.0304947724757",
          "2": "744.4863354919623",
          "3": "708.9787894178695",
          "4": "780.3828727587787",
          "5": "617.3855334765709",
          "6": "681.2571553421462"
        }
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

      var highestY = 1000;
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
        for (let [index, element] of Object.entries(value)) {
          points[cpt++] = [cpt*((width-100)/numberElements), height/2 - ((height/(2*highestY))*parseFloat(element))];
        };
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