import tkinter.filedialog as filedialog
import tkinter as tk
import subprocess
# from convert_rdb import rdb_to_csv

master = tk.Tk()
master.title("zet rdb file over in csv file")

def input1():
    print("ik ben in functie input1")
    input_path = filedialog.askopenfilename()
    print("input_path:", input_path)
    input_entry1.delete(1, tk.END)  # Remove current text in entry
    input_entry1.insert(0, input_path)  # Insert the 'path'

def press_button():
    print("ik ben in functie press_button")
    input_paths = [input_entry1.get()]
    try:
        subprocess.run(["python", "convert_rdb.py", *input_paths], check=True)
        print("ik voer panda-script uit")
    except subprocess.CalledProcessError as e:
        print(f"fout bij het uitvoeren van het panda-script: {e}")
        print("ik kan geen panda-script uitvoeren")

top_frame = tk.Frame(master)
bottom_frame = tk.Frame(master)
line = tk.Frame(master, height=1, width=400, bg="grey80", relief='groove')

input_label1 = tk.Label(top_frame, text="voeg hier de rdb file toe")
input_entry1 = tk.Entry(top_frame, width=40)
browse1 = tk.Button(top_frame, text="Browse", command=input1)

begin_button = tk.Button(bottom_frame, text='run script', command=press_button)

top_frame.pack(side=tk.TOP)
line.pack(pady=10) 
bottom_frame.pack(side=tk.BOTTOM)

input_label1.grid(row=0, column=0, padx=10, pady=5, sticky='w')
input_entry1.grid(row=0, column=1, padx=10, pady=5, columnspan=2)
browse1.grid(row=0, column=3, padx=10, pady=5)

begin_button.grid(row=7, column=0, columnspan=4, pady=20)

master.mainloop()