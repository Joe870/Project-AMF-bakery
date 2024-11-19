import sqlite3
import pandas as pd
import os

def rdb_to_csv(rdb_path, output_folder):
    # Ensure the output folder exists
    if not os.path.exists(output_folder):
        os.makedirs(output_folder)

    # Connect to the SQLite database (the .rdb file)
    conn = sqlite3.connect(rdb_path)
    cursor = conn.cursor()

    # Get all table names in the database
    cursor.execute("SELECT name FROM sqlite_master WHERE type='table';")
    tables = cursor.fetchall()

    # Loop through each table and export it to a CSV file
    for table_name_tuple in tables:
        table_name = table_name_tuple[0]  # Extract the table name from the tuple

        # Use pandas to read the table into a DataFrame
        df = pd.read_sql_query(f"SELECT * FROM {table_name}", conn)

        # Set up the CSV file path in the output folder
        csv_file_path = os.path.join(output_folder, f"{table_name}.csv")

        # Save the DataFrame as a CSV file
        df.to_csv(csv_file_path, index=False)
        print(f"Exported {table_name} to {csv_file_path}")

    # Close the database connection
    conn.close()
    print("All tables have been exported to CSV.")

# Define the path to your .rdb file and the output folder
rdb_path = r"C:\Users\joell\Documents\Studie software developer\Sofware developer\Bovenbouw\Joëlle\projecten\code\Python\AlarmBuffer0.rdb"    # Replace with the actual path to your .rdb file
output_folder = r"C:\Users\joell\Documents\Studie software developer\Sofware developer\Bovenbouw\Joëlle\projecten\code\Python\test" # Replace with the path to your desired output folder

# Run the conversion function
rdb_to_csv(rdb_path, output_folder)