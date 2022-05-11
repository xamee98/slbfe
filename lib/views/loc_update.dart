import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:http/http.dart';
import 'package:slbfe/views/user_login.dart';

class LocationUpdate extends StatelessWidget {
  const LocationUpdate({Key? key}) : super(key: key);

  // final deviceToken = "ccb2f86ca1d88bc2c4625220f526b808";

  @override
  Widget build(BuildContext context) {
    TextEditingController nidController = TextEditingController();
    TextEditingController latController = TextEditingController();
    TextEditingController longController = TextEditingController();

    return SafeArea(
      child: Scaffold(
        appBar: AppBar(
          centerTitle: true,
          title: Text(
            'Update Current Location',
          ),
        ),
        body: Padding(
          padding: const EdgeInsets.all(8.0),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            mainAxisSize: MainAxisSize.max,
            children: [
              TextField(
                controller: nidController,
                decoration: InputDecoration(
                  border: OutlineInputBorder(),
                  labelText: 'ID',
                ),
              ),
              Container(
                height: 20,
              ),
              TextField(
                obscureText: false,
                controller: latController,
                decoration: InputDecoration(
                  border: OutlineInputBorder(),
                  labelText: 'New Latitude',
                ),
              ),
              Container(
                height: 20,
              ),
              TextField(
                obscureText: false,
                controller: longController,
                decoration: InputDecoration(
                  border: OutlineInputBorder(),
                  labelText: 'New Longitude',
                ),
              ),
              Container(
                height: 20,
              ),
              TextButton(
                child: Container(
                  height: 50.0,
                  width: double.infinity,
                  child: Card(
                    shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(15.0)),
                    color: Colors.blue,
                    child: const Center(
                      child: Text(
                        'Update Location',
                        style: TextStyle(
                          fontSize: 16,
                          color: Colors.white,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  ),
                ),
                onPressed: () async {
                  print('Update Location');

                  String url =
                      "https://192.168.1.150/API/?action=update_user_details";
                  String nid = nidController.text;
                  String lat = latController.text;
                  String long = longController.text;

                  var response = await post(Uri.parse(url), body: {
                    "national_id": nid,
                    "device_token": "0ae8baea7d33f40f2d97fbc7bd5a846e",
                    "latitude": lat,
                    "longitude": long
                  });

                  print((response.body));

                  print(deviceToken);
                },
              ),
            ],
          ),
        ),
      ),
    );
  }
}
