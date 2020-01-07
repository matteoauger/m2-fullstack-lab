import React from 'react';
import * as d3 from 'd3';

class TimeSeriesGraph extends React.Component {
  componentDidMount() {
    this.drawChart();
  }
    
  drawChart() {
    const data = [
      {
        "current_date": "2015-01-01 00:00:00",
        "mean": "849.2742321679365"
      },
      {
        "current_date": "2015-02-01 00:00:00",
        "mean": "778.1066660163153"
      },
      {
        "current_date": "2015-03-01 00:00:00",
        "mean": "777.1686411783287"
      },
      {
        "current_date": "2015-04-01 00:00:00",
        "mean": "783.0169727654051"
      },
      {
        "current_date": "2015-05-01 00:00:00",
        "mean": "729.2404162140479"
      },
      {
        "current_date": "2015-06-01 00:00:00",
        "mean": "924.7870563198281"
      },
      {
        "current_date": "2015-07-01 00:00:00",
        "mean": "797.3464758235984"
      },
      {
        "current_date": "2015-08-01 00:00:00",
        "mean": "731.534396075957"
      },
      {
        "current_date": "2015-09-01 00:00:00",
        "mean": "783.3311370966198"
      },
      {
        "current_date": "2015-10-01 00:00:00",
        "mean": "1107.169184188551"
      },
      {
        "current_date": "2015-11-01 00:00:00",
        "mean": "809.9815936887339"
      },
      {
        "current_date": "2015-12-01 00:00:00",
        "mean": "1023.6415059316773"
      },
      {
        "current_date": "2016-01-01 00:00:00",
        "mean": "1877.3703445632943"
      },
      {
        "current_date": "2016-02-01 00:00:00",
        "mean": "765.2986682775818"
      },
      {
        "current_date": "2016-03-01 00:00:00",
        "mean": "922.1649107875346"
      },
      {
        "current_date": "2016-04-01 00:00:00",
        "mean": "789.2226053618164"
      },
      {
        "current_date": "2016-05-01 00:00:00",
        "mean": "825.7597913028305"
      },
      {
        "current_date": "2016-06-01 00:00:00",
        "mean": "764.1688707872167"
      },
      {
        "current_date": "2016-07-01 00:00:00",
        "mean": "1285.4887774445272"
      },
      {
        "current_date": "2016-08-01 00:00:00",
        "mean": "776.0905633913991"
      },
      {
        "current_date": "2016-09-01 00:00:00",
        "mean": "880.1893218233422"
      },
      {
        "current_date": "2016-10-01 00:00:00",
        "mean": "907.1672711711467"
      },
      {
        "current_date": "2016-11-01 00:00:00",
        "mean": "890.137659513001"
      },
      {
        "current_date": "2017-01-01 00:00:00",
        "mean": "1747.2659925369273"
      },
      {
        "current_date": "2017-02-01 00:00:00",
        "mean": "821.7336110962607"
      },
      {
        "current_date": "2017-03-01 00:00:00",
        "mean": "877.2092107653439"
      },
      {
        "current_date": "2017-04-01 00:00:00",
        "mean": "1001.383521824255"
      },
      {
        "current_date": "2017-05-01 00:00:00",
        "mean": "857.7831214989175"
      },
      {
        "current_date": "2017-06-01 00:00:00",
        "mean": "814.9783692550577"
      },
      {
        "current_date": "2017-07-01 00:00:00",
        "mean": "919.5625940880805"
      },
      {
        "current_date": "2017-08-01 00:00:00",
        "mean": "818.2672603241061"
      },
      {
        "current_date": "2017-09-01 00:00:00",
        "mean": "845.7610408915579"
      },
      {
        "current_date": "2017-10-01 00:00:00",
        "mean": "1174.2187329171031"
      },
      {
        "current_date": "2017-11-01 00:00:00",
        "mean": "1423.9742214995954"
      },
      {
        "current_date": "2017-12-01 00:00:00",
        "mean": "1042.7502286023487"
      },
      {
        "current_date": "2018-01-01 00:00:00",
        "mean": "920.8322452167349"
      },
      {
        "current_date": "2018-02-01 00:00:00",
        "mean": "833.5533888422788"
      },
      {
        "current_date": "2018-03-01 00:00:00",
        "mean": "823.6574770290622"
      },
      {
        "current_date": "2018-05-01 00:00:00",
        "mean": "778.2389410899071"
      },
      {
        "current_date": "2018-06-01 00:00:00",
        "mean": "5662.224800032189"
      },
      {
        "current_date": "2018-07-01 00:00:00",
        "mean": "834.0376271482407"
      },
      {
        "current_date": "2018-08-01 00:00:00",
        "mean": "815.7647917532094"
      },
      {
        "current_date": "2018-09-01 00:00:00",
        "mean": "935.082447104722"
      },
      {
        "current_date": "2018-10-01 00:00:00",
        "mean": "1121.7484912777427"
      },
      {
        "current_date": "2018-11-01 00:00:00",
        "mean": "913.2795575402716"
      },
      {
        "current_date": "2018-12-01 00:00:00",
        "mean": "1330.0343430409143"
      },
      {
        "current_date": "2019-01-01 00:00:00",
        "mean": "842.6868106443874"
      },
      {
        "current_date": "2019-02-01 00:00:00",
        "mean": "1498.912946344018"
      },
      {
        "current_date": "2019-03-01 00:00:00",
        "mean": "3867.0899366318317"
      },
      {
        "current_date": "2019-05-01 00:00:00",
        "mean": "757.0171190163037"
      },
      {
        "current_date": "2019-06-01 00:00:00",
        "mean": "799.0834976805838"
      }
    ]
    // Adapt data.
    .map(({ current_date, mean }) => {
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
      <div class="chart">
        <h2>Prix moyen du m2</h2>
        <div id='graph'></div>
      </div>
    )
  }
}
    
export default TimeSeriesGraph;