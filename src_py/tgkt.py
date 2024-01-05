import random
import json
from flask import Flask, request, jsonify
from flask_cors import CORS
import sys
app = Flask(__name__)
CORS(app)

def htmlspecialchars(text):
    return (
        text.replace("&", "&amp;").
        replace('"', "&quot;").
        replace("<", "&lt;").
        replace(">", "&gt;")
    )

if len(sys.argv) > 1:
    file_path = sys.argv[1]
else:
    file_path = "/home/orange/TangokotoAPI/Tangokoto.txt"  # path

with open(file_path, "r", encoding="utf-8") as file:
    tangokoto = file.readlines()

author = "唐老师"
length = len(tangokoto)

@app.route('/pytangokoto', methods=['GET'])
def get_tangokoto():
    randid = random.randint(0, length - 1)
    index = int(request.args.get('id', randid))

    index = randid if index < 0 or index >= length else index

    output_raw = tangokoto[index].rstrip()
    output = {
        'id': index,
        'hitokoto': output_raw,
        'from': author
    }

    output_type = request.args.get('type', 'json')

    if output_type == 'plain':
        return output_raw, 200, {'Content-Type': 'text/plain; charset=utf-8'}
    elif output_type == 'js':
        js_output = f"function doTangokoto(){{document.write(\"<span class='hitokoto' title='Tangokoto'>{htmlspecialchars(output_raw)}</span>\");}}"
        return js_output, 200, {'Content-Type': 'application/javascript; charset=utf-8'}
    elif output_type == 'jsonp':
        callback = request.args.get('_callback', 'doTangokoto')
        jsonp_output = f"{callback}({json.dumps({k: htmlspecialchars(v) for k, v in output.items()})})"  # JSONP output
        return jsonp_output, 200, {'Content-Type': 'application/javascript; charset=utf-8'}
    else:
        return jsonify(output), 200, {'Content-Type': 'application/json; charset=utf-8'}

if __name__ == '__main__':
    app.run()

# edited: file path, api path, xss defend, id.
