import React from 'react';
import * as d3 from 'd3';
import { fetch } from '../../utils/dataAccess';
import MONTHS from '../../utils/months';

class BarChart extends React.Component {

    constructor(props) {
        super(props);
        this.fetchData = this.fetchData.bind(this);
        this.changeInterval = this.changeInterval.bind(this);
        this.changeStartDate = this.changeStartDate.bind(this);
        this.changeEndDate = this.changeEndDate.bind(this);

        this.state = {
            data: [],
            interval: 'month',
            startDate: '2015-01-01',
            endDate: '2016-01-01'
        };
    }

    /** @override */
    componentDidMount() {
        this.fetchData();
    }

    /** @override */
    componentDidUpdate(_, prevState) {
        if (prevState.interval !== this.state.interval || prevState.startDate !== this.state.startDate || prevState.endDate !== this.state.endDate) {
            this.fetchData();
        }
        d3.select('#graph').remove();
        if (prevState.data !== this.state.data) {
            this.drawChart();
        }
    }

    changeInterval(event) {
        this.setState({
            interval: event.target.value
        });
    }

    changeStartDate(event) {
        this.setState({
            startDate: event.target.value
        });
    }

    changeEndDate(event) {
        this.setState({
            endDate: event.target.value
        });
    }

    fetchData() {
        const options = {
            method: 'GET',
            mode: 'cors',
            cache: 'default'
        };
        fetch(`land_value_claims/salesbyinterval?interval=${encodeURIComponent(this.state.interval)}&date_start=${encodeURIComponent(this.state.startDate)}&date_end=${encodeURIComponent(this.state.endDate)}`, options)
            .then((response) => {
                response.json().then((data) => {
                    this.setState({ data });
                });
            });
    }

    drawChart() {
        const data = this.state.data;
        const nbData = data.length;
        const maxCount = d3.max(data, d => d.sales_count);
        const width = 1000;
        const height = 500;
        const barWidth = width / nbData;

        const svg = d3.select('#barchart')
            .append('svg')
            .attr('id', 'graph')
            .attr('width', width)
            .attr('height', height)
            .style('margin-left', 100);

        const tooltip = d3.select('#labels')
            .append('div')
            .attr('class', 'tooltip')
            .style('opacity', 0);

        const y_scale = d3.scaleLinear()
            .domain([0, maxCount])
            .range([height, 0]);

        const y_axis = d3.axisLeft().scale(y_scale);

        svg.append('g')
            .attr('transform', 'translate(50, 10)')
            .call(y_axis);

        const self = this;
        svg.selectAll('all')
            .data(data)
            .enter()
            .append('rect')
            .attr('x', (_, i) => 52 + i * barWidth)
            .attr('y', (d, _) => height - (d.sales_count / maxCount) * height)
            .attr('width', barWidth - 1)
            .attr('height', (d, _) => (d.sales_count / maxCount) * height)
            .attr('fill', '#9d5c63')
            .on('mouseover', function (d, i) {
                d3.select(this).attr('fill', '#8b2635');
                const date = new Date(d.current_date);
                let htmlContent = '';

                // writing the day
                if (self.state.interval === 'day') {
                    htmlContent += ' ' + date.getDay();
                }

                // writing the month
                if (self.state.interval === 'day' || self.state.interval === 'month') {
                    htmlContent += ' ' + MONTHS[date.getMonth()];
                }

                // writing the year and the sales count
                htmlContent += ' ' + date.getFullYear();
                htmlContent += '<br/>' + d.sales_count + ' ventes';

                tooltip.html(htmlContent)
                    .style('font-size', '20px')
                    .style('text-anchor', 'middle')
                    .style('left', (52 + (i + 1) * barWidth + barWidth / 2) + 'px');
                tooltip.style('opacity', 1);
            })
            .on('mouseout', function () {
                d3.select(this).attr('fill', '#9d5c63');
                tooltip.style('opacity', 0);
            });
    }

    /** @override */
    render() {
        return (
            <div className='chart'>
                <title>Nombre de ventes</title>
                <select value={this.state.interval} onChange={this.changeInterval}>
                    <option value='day'>Jour</option>
                    <option value='month'>Mois</option>
                    <option value='year'>Année</option>
                </select>
                <input type='date' name='startDate' value={this.state.startDate} onChange={this.changeStartDate}></input>
                <input type='date' name='endDate' value={this.state.endDate} onChange={this.changeEndDate}></input>
                <div id='barchart'></div>
                <div id='labels'></div>
            </div>
        );
    }
};

export default BarChart;