// To parse this JSON data, do
//
//     final registerModel = registerModelFromJson(jsonString);

import 'dart:convert';

RegisterModel registerModelFromJson(String str) =>
    RegisterModel.fromJson(json.decode(str));

String registerModelToJson(RegisterModel data) => json.encode(data.toJson());

class RegisterModel {
  RegisterModel({
    required this.age,
    required this.name,
    required this.address,
    required this.currentLocation,
    required this.profession,
    required this.email,
    required this.affiliation,
    required this.password,
  });

  String age;
  String name;
  String address;
  String currentLocation;
  String profession;
  String email;
  String affiliation;
  String password;

  factory RegisterModel.fromJson(Map<String, dynamic> json) => RegisterModel(
        age: json["age"],
        name: json["name"],
        address: json["address"],
        currentLocation: json["current_location"],
        profession: json["profession"],
        email: json["email"],
        affiliation: json["affiliation"],
        password: json["password"],
      );

  Map<String, dynamic> toJson() => {
        "age": age,
        "name": name,
        "address": address,
        "current_location": currentLocation,
        "profession": profession,
        "email": email,
        "affiliation": affiliation,
        "password": password,
      };
}
