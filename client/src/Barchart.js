import React from 'react';
import * as d3 from 'd3';

class Barchart extends React.Component {
    componentDidMount() {
      this.drawChart();
    }
 
    static_data() {
        return [
            { 
                "current_date": "2019-01-02",
                "sales_count": 1000
            },
            { 
                "current_date": "2019-01-03",
                "sales_count": 536
            },
            { 
                "current_date": "2019-01-04",
                "sales_count": 340
            },
            { 
                "current_date": "2019-01-05",
                "sales_count": 860
            },
            { 
                "current_date": "2019-01-06",
                "sales_count": 910
            },{ 
                "current_date": "2019-01-06",
                "sales_count": 453
            },{ 
                "current_date": "2019-01-06",
                "sales_count": 752
            },{ 
                "current_date": "2019-01-06",
                "sales_count": 962
            },{ 
                "current_date": "2019-01-06",
                "sales_count": 357
            },{ 
                "current_date": "2019-01-06",
                "sales_count": 852
            },{ 
                "current_date": "2019-01-06",
                "sales_count": 354
            },{ 
                "current_date": "2019-01-06",
                "sales_count": 753
            },{ 
                "current_date": "2019-01-06",
                "sales_count": 159
            },{ 
                "current_date": "2019-01-06",
                "sales_count": 357
            },{ 
                "current_date": "2019-01-06",
                "sales_count": 854
            },{ 
                "current_date": "2019-01-06",
                "sales_count": 910
            },{ 
                "current_date": "2019-01-06",
                "sales_count": 654
            },{ 
                "current_date": "2019-01-06",
                "sales_count": 456
            },{ 
                "current_date": "2019-01-06",
                "sales_count": 658
            },{ 
                "current_date": "2019-01-06",
                "sales_count": 851
            },{ 
                "current_date": "2019-01-06",
                "sales_count": 359
            },{ 
                "current_date": "2019-01-06",
                "sales_count": 645
            }
        ]
    }

    drawChart() {
        const data = this.static_data();
        const nbData = data.length;
        const maxData = d3.max(data, (d) => { return d.sales_count });
        const width = 1000;
        const height = 500;
        const barWidth = width/nbData;

        const svg = d3.select("#svg1")
            .append("svg")
            .attr("width", width)
            .attr("height", height)
            .style("margin-left", 100);

        svg.selectAll("rect")
            .data(data)
            .enter()
            .append("rect")
            .attr("x", (d, i) => i * barWidth)
            .attr("y", (d, i) => height - (d.sales_count/maxData)*(height-20))
            .attr("width", barWidth - 1)
            .attr("height", (d, i) => (d.sales_count/maxData)*(height-20))
            .attr("fill", "#0066cc")
            .on("mouseover", function(d) {
                const x = this.x;
                d3.select(this).style("fill", "#0080ff")
                .selectAll("text")
                .data(d)
                .enter()
                .append("text")
                .text((d) => d.sales_count)
                .attr("x", () => x)
                .attr("y", (d) => height - (d.sales_count/maxData)*(height-15));
            })                  
            .on("mouseout", function(d) {
                d3.select(this).style("fill", "#0066cc");
            });


        svg.selectAll("text")
            .data(data)
            .enter()
            .append("text")
            .text((d) => d.sales_count)
            .attr("x", (d, i) => i * barWidth)
            .attr("y", (d, i) => height - (d.sales_count/maxData)*(height-15))
    }

    render() {
        return <body>
            <h1>THIS IS A TEST</h1>
            <g id="svg1"></g>
        </body>
    }
};

export default Barchart;
