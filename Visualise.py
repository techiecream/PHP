import pandas as pd
import matplotlib.pyplot as plt

# Load the sales data from a CSV file into a Pandas DataFrame
df = pd.read_csv('sales_data.csv')

# Clean the data by removing missing values, duplicates, and outliers
df.dropna(inplace=True)  # Remove missing values
df.drop_duplicates(inplace=True)  # Remove duplicates
z_scores = (df['cost'] - df['cost'].mean()) / df['cost'].std()
df = df[(z_scores < 3) & (z_scores > -3)]  # Remove outliers using a Z-score approach

# Calculate the total sales by week in every year
# First, convert the 'Date' column to datetime format
df['Date'] = pd.to_datetime(df['Date'])
# Then, group the data by year and month, and calculate the total sales
sales_by_week = df.groupby([df['Date'].dt.year, df['Date'].dt.month])['cost'].sum()

# Create a bar chart to visualize the total sales by week in every year
sales_by_week.plot(kind='bar', title='Total Sales per Month')

# Display the chart
plt.show()
