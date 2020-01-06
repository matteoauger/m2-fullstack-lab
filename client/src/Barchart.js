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

        const x_scale = d3.scaleLinear()
            .domain([0, nbData])
            .range([new Date(data[0].current_date), new Date(data[nbData-1].current_date)]);

        const x_axis = d3.axisBottom().scale(x_scale);

        const y_scale = d3.scaleLinear()
            .domain([0, maxData])
            .range([height-25, 0]);

        const y_axis = d3.axisLeft().scale(y_scale);

        svg.append("g")
            .attr("transform", "translate(50, 20)")
            .call(y_axis);

        svg.append("g")
            .call(x_axis);    

        svg.selectAll("rect")
            .data(data)
            .enter()
            .append("rect")
            .attr("x", (d, i) => 52+i * barWidth)
            .attr("y", (d, i) => height - (d.sales_count/maxData)*(height-20))
            .attr("width", barWidth - 1)
            .attr("height", (d, i) => (d.sales_count/maxData)*(height-20) - 3)
            .attr("fill", "#0066cc")
            .on("mouseover", function(d, i) {
                d3.select(this).attr("fill", "#0080ff");
                tooltip.html(d.current_date+"<br>"+d.sales_count + " ventes")
                    .style("font-size", "20px")
                    .style("text-anchor", "middle")
                    .style("left", (52+ (i+1) * barWidth + barWidth/2) + "px")		
                tooltip.style("opacity", 1);
            })
            .on("mouseout", function(d) {
                d3.select(this).attr("fill", "#0066cc");
                tooltip.style("opacity", 0);
            });
    }

    render() {
        return <body>
            <h1>Nombre de ventes</h1>
            <div id="barchart"></div>
            <div id="labels"></div>
        </body>
    }
};

export default Barchart;
