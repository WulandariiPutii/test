from flask import Flask, request, jsonify, make_response
import pymysql

nasabah = Flask(__name__)

@nasabah.route('/')
@nasabah.route('/index')
def index():
    return "Hello, world!"

mydb = pymysql.connect(
    host="localhost",
    user="root",
    passwd="",
    database="db_nasabah"
)

@nasabah.route('/post_data', methods=['POST'])
def web_command():
    hasil = {"status": "gagal"}
    query = "insert into tb_daftar(nama, nik, no_hp, no_rekening, saldo) values(%s, %s, %s, %s, %s)"
    try:
        data = request.json
        nama = data["nama"]
        nik = data["nik"]
        no_hp = data["no_hp"]
        no_rekening = data["no_rekening"]
        saldo = data["saldo"]
        value = (nama, nik, no_hp, no_rekening, saldo)
        mycursor = mydb.cursor()
        mycursor.execute(query, value)
        mydb.commit()
        hasil = {"status": "berhasil"}
    except Exception as e:
        print("Error: " + str(e))

    return jsonify(hasil)

@nasabah.route('/get_data', methods=['GET'])
def web_sensor():
    query = "SELECT * FROM tb_daftar"

    mycursor = mydb.cursor()
    mycursor.execute(query)
    row_headers = [x[0] for x in mycursor.description]
    data = mycursor.fetchall()
    json_data = []
    for result in data:
        json_data.append(dict(zip(row_headers, result)))
    mydb.commit()
    return make_response(jsonify(json_data),200)

if __name__ == '__main__':
    nasabah.run(host='0.0.0.0', port=5010)