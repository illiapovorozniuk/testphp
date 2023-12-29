import { FC, useState, useEffect } from "react";
import { Form } from "react-bootstrap";
import { IBooking, ISource } from "../types/IBooking";

interface IProps {
  setTableData: (state: IBooking[]) => void;
}

const FilterForm: FC<IProps> = ({ setTableData }) => {
  const [requestBody, setRequestBody] = useState<IBooking>({
    booking_id: 1,
    car_id: 1,
    source: "carsss",
  });

  const [booking_id, setBooking_id] = useState<number>(1);
  const [car_id, setCar_id] = useState<number>(1);
  const [source, setSource] = useState<"carsss" | "direct" | "other">("carsss");

  useEffect(() => {
    getFilterBooking(requestBody);
  }, []);

  const handleChange = () => {
    setRequestBody({
      booking_id: booking_id,
      car_id: car_id,
      source: source,
    });
  };
  useEffect(() => {
    handleChange();
  }, [booking_id, car_id, source]);

  const getFilterBooking = async (requestBody: IBooking) => {
    try {
      fetch("http://localhost:8000/api", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(requestBody),
      })
        .then((response) => response.json())
        .then((data: IBooking[]) => {
          console.log(data[0]);
          setTableData(data);
        });
    } catch (error) {
      console.error("Error fetching data:", error);
    }
  };

  return (
    <div className="">
      <form>
        <label>
          Select Option 1:
          <select
            value={booking_id}
            onChange={(e) => {
              setBooking_id(Number(e.target.value));
              getFilterBooking({
                ...requestBody,
                booking_id: Number(e.target.value),
              });
            }}
          >
            <option value={0}>Select an option</option>
            <option value={1}>Option 1</option>
            <option value={62}>Option 62</option>
            <option value={10}>Option 10</option>
          </select>
        </label>

        <br />
      </form>
    </div>
  );
};

export default FilterForm;
